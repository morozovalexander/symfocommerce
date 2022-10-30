<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Structs\CartContents;
use App\Service\Structs\CartProductPosition;

class Cart
{
    /** @var ProductRepository */
    private $productRepository;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    /**
     * @param int[] $quantityByProductId
     * @return CartContents
     */
    public function getCartContents(array $quantityByProductId): CartContents
    {
        $positions = [];
        $totalSum = 0.0;
        $productsById = $this->findProductsByIds(array_keys($quantityByProductId));

        foreach ($quantityByProductId as $productId => $productQuantity) {
            if (!isset($productsById[$productId])) {
                continue;
            }
            $product = $productsById[$productId];
            $quantity = abs($productQuantity);
            $price = $product->getPrice();
            $sum = $price * $quantity;

            $positions[] = new CartProductPosition($product, $quantity, $price, $sum);
            $totalSum += $sum;
        }
        return new CartContents($positions, $totalSum);
    }

    /**
     * @param int[] $productIds
     * @return Product[] indexed by product id
     */
    public function findProductsByIds(array $productIds = []): array
    {
        $products = $this->productRepository->findBy(['id' => $productIds]);
        $productsById = [];
        array_map(function ($product) use (&$productsById) {
            $productsById[$product->getId()] = $product;
        },
        $products);
        return $productsById;
    }
}
