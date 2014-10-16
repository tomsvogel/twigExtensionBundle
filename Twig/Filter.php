<?php
namespace Arkulpa\Bundle\TwigExtensionBundle\Twig;


class Filter extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'sortAsc' => new \Twig_Filter_Method($this, 'sortDateAsc'),
        );
    }

    public function sortAsc($source, $field)
    {


        return $source;
    }


    public function getName()
    {
        return 'arkulpa_filter';
    }
}
