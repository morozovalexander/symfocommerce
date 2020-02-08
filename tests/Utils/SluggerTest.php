<?php

namespace App\Tests\Utils;

use Eshop\FixturesBundle\Utils\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSlugify($string, $slug)
    {
        $this->assertSame($slug, Slugger::slugify($string));
    }

    public function getSlugs()
    {
        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield ['  Lorem Ipsum  ', 'lorem-ipsum'];
        yield [' lOrEm  iPsUm  ', 'lorem-ipsum'];
        yield ['!Lorem Ipsum!', '!lorem-ipsum!'];
        yield ['lorem-ipsum', 'lorem-ipsum'];
        yield ['lorem 日本語 ipsum', 'lorem-日本語-ipsum'];
        yield ['lorem русский язык ipsum', 'lorem-русский-язык-ipsum'];
        yield ['lorem العَرَبِيَّة‎‎ ipsum', 'lorem-العَرَبِيَّة‎‎-ipsum'];
    }

    public function testThatMyComputerWorks()
    {
        $this->assertTrue(true);
    }
}
