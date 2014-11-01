<?php
namespace Arkulpa\Bundle\TwigExtensionBundle\Twig;


use Doctrine\Tests\DBAL\Types\DateTest;
use Symfony\Component\Validator\Constraints\DateTime;

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
