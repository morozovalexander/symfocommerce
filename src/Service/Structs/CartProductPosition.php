<?php

namespace App\Service\Structs;

use App\Entity\Product;

class CartProductPosition
{
    /** @var Product */
    public $product;
    /** @var int */
    public $quantity;
    /** @var float */
    public $price;
    /** @var float */
    public $sum;

    /**
     * @param Product $product
     * @param int $quantity
     * @param float $price
     * @param float $sum
     */
    public function __construct(Product $product, int $quantity, float $price, float $sum)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->sum = $sum;
    }
}
