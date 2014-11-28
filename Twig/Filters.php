<?php
namespace Arkulpa\Bundle\TwigExtensionBundle\Twig;


use Doctrine\ORM\PersistentCollection;

class Filters extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'sortArray', array($this, 'sortArrayFnc')
            ),
            new \Twig_SimpleFilter(
                'removePast', array($this, 'removePastFnc')
            ),
            new \Twig_SimpleFilter(
                'objectSort', array($this, 'objectSort')
            ),
        );
    }

    function sortArrayFnc($arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);

        return $arr;
    }


    function objectSort($values, $methodNames, $dir = 'asc')
    {
        if ($values instanceof PersistentCollection) {
            $values = $values->toArray();
        }
        if (!is_array($methodNames)) {
            $methodNames = array($methodNames);
        }
        usort(
            $values,
            function ($a, $b) use ($methodNames) {
                foreach ($methodNames as $i => $methodName) {
                    $aOrder = $a->$methodName();
                    $bOrder = $b->$methodName();
                    if (is_string($aOrder)) {
                        $aOrder = strtolower($aOrder);
                        $bOrder = strtolower($bOrder);
                    }
                    if ($aOrder == $bOrder) {
                        if (count($methodNames) == ($i + 1)) {
                            return 0;
                        } else {
                            continue;
                        }
                    }
                    return ($aOrder < $bOrder) ? -1 : 1;
                }
            }
        );
        if (strtolower($dir) == 'desc') {
            $values = array_reverse($values);
        }
        return $values;
    }


    function removePastFnc($arr, $col)
    {
        $result = array();
        $d = new \DateTime();
        $dstr = $d->format('Y-m-d');
        foreach ($arr as $a) {
            if ($a[$col] instanceof \DateTime) {
                if ($a[$col]->format('Y-m-d') >= $dstr) {
                    $result[] = $a;
                }
            } else {
                if ($a[$col] >= $dstr) {
                    $result[] = $a;
                }
            }

        }

        return $result;
    }


    public function getName()
    {
        return 'arkulpa_filter';
    }
}
