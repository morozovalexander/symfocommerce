<?php

namespace Eshop\FixturesBundle\Utils;

class Slugger
{
    /**
     * @param string $string
     * @return string
     */
    public static function slugify(string $string): string
    {
        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }
}
