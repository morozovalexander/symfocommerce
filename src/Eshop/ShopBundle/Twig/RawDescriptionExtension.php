<?php

namespace Eshop\ShopBundle\Twig;

class RawDescriptionExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('rawdescr', [$this, 'rawdescrFilter'], ['is_safe' => ['html']])
        ];
    }

    public function rawdescrFilter($descr)
    {
        return $descr;
    }
}
