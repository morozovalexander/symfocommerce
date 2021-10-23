<?php

namespace App\Service\Structs;

class CartContents
{
    /** @var CartProductPosition[] */
    public $positions;
    /** @var float */
    public $totalSum;

    /**
     * @param CartProductPosition[] $positions
     * @param float $totalSum
     */
    public function __construct(array $positions, float $totalSum)
    {
        $this->positions = $positions;
        $this->totalSum = $totalSum;
    }
}
