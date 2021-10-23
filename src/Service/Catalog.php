<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

class Catalog
{
    /** @var ProductRepository */
    private $productRepository;
    /** @var Security */
    private $security;
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * @param ProductRepository $productRepository
     * @param Security $security
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        ProductRepository $productRepository,
        Security $security,
        PaginatorInterface $paginator
    ) {
        $this->productRepository = $productRepository;
        $this->security = $security;
        $this->paginator = $paginator;
    }

    /**
     * @param int $quantity
     * @return Product[]
     */
    public function getLatestProducts(int $quantity = 12): array
    {
        return $this->productRepository->getLatest($quantity, $this->security->getUser());
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
        $query = $this->productRepository->findByCategoryQB($category, $this->security->getUser());
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
        $query = $this->productRepository->findByManufacturerQB($manufacturer, $this->security->getUser());
        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @param int $quantity
     * @return Product[]
     */
    public function getFeaturedProducts(int $quantity = 12): array
    {
        return $this->productRepository->getLatest($quantity, $this->security->getUser());
    }

    /**
     * @param int $limit
     * @param int $page
     * @return Iterable|Product[]
     */
    public function getFavouriteProducts(int $limit, int $page = 1): array
    {
        $query = $this->productRepository->getFavouritesQB($this->security->getUser());
        return $this->paginator->paginate($query, $page, $limit);
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

        $qb = $this->productRepository->getSearchQB($searchWords, $this->security->getUser());
        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * @param int[] $productIds
     * @return Product[]
     */
    public function getLastSeenProducts(array $productIds): array
    {
        return $this->productRepository->getLastSeen($productIds, $this->security->getUser(), 4);
    }
}
