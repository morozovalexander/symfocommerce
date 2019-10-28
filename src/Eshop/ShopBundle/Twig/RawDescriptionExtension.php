<?php

namespace Eshop\ShopBundle\Twig;

class RawDescriptionExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('rawdescr', [$this, 'rawdescrFilter'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param string $descr
     * @return string
     */
    public function rawdescrFilter(string $descr): string
    {
        return $descr;
    }
}
