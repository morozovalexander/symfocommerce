<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class Catalog
{
    /** @var ProductRepository */
    private $productRepository;
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * @param ProductRepository $productRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        ProductRepository $productRepository,
        PaginatorInterface $paginator
    ) {
        $this->productRepository = $productRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param int $quantity
     * @return Product[]
     */
    public function getLatestProducts(int $quantity = 12): array
    {
        return $this->productRepository->getLatest($quantity);
    }

    /**
     * @param Category $category
     * @param int $limit
     * @param int $page
     * @return Iterable|Product[]
     */
    public function getProductsByCategory(
        Category $category,
        int $limit,
        int $page = 1
    ): SlidingPaginationInterface {
        $query = $this->productRepository->findByCategoryQB($category);
        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @param Manufacturer $manufacturer
     * @param int $limit
     * @param int $page
     * @return Iterable|Product[]
     */
    public function getProductsByManufacturer(
        Manufacturer $manufacturer,
        int $limit,
        int $page = 1
    ): SlidingPaginationInterface {
        $query = $this->productRepository->findByManufacturerQB($manufacturer);
        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @param int $quantity
     * @return Product[]
     */
    public function getFeaturedProducts(int $quantity = 12): array
    {
        return $this->productRepository->getFeatured($quantity);
    }

    /**
     * @param int $limit
     * @param int $page
     * @param string $searchPhrase
     * @return Iterable|Product[]
     */
    public function searchProduct(int $limit, int $page, string $searchPhrase = ''): array
    {
        $searchWords = explode(' ', trim($searchPhrase));

        $qb = $this->productRepository->getSearchQB($searchWords);
        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * @param int[] $productIds
     * @return Product[]
     */
    public function getLastSeenProducts(array $productIds): array
    {
        return $this->productRepository->getLastSeen($productIds, 4);
    }
}
