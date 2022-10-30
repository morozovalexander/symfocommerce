<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RawDescriptionExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('rawdescr', [$this, 'rawdescrFilter'], ['is_safe' => ['html']])
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
