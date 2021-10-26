<?php

namespace App\Service;

use App\Entity\Favourites;
use App\Entity\Product;
use App\Repository\FavouritesRepository;
use App\Repository\ProductRepository;
use App\Service\Structs\ProductLikeResult;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

class FavouriteProducts
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var ProductRepository */
    private $productRepository;
    /** @var FavouritesRepository */
    private $favouritesRepository;
    /** @var Security */
    private $security;
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * @param EntityManagerInterface $em
     * @param ProductRepository $productRepository
     * @param FavouritesRepository $favouritesRepository
     * @param Security $security
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $productRepository,
        FavouritesRepository $favouritesRepository,
        Security $security,
        PaginatorInterface $paginator
    ) {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->favouritesRepository = $favouritesRepository;
        $this->security = $security;
        $this->paginator = $paginator;
    }

    /**
     * @param int $productId
     * @return ProductLikeResult
     */
    public function toggleProductLike(int $productId): ProductLikeResult
    {
        $result = new ProductLikeResult();
        $product = $this->productRepository->find($productId);
        if (!$product) {
            $result->message = 'productnotfound';
            $result->success = false;
            return $result;
        }

        $user = $this->security->getUser();
        if (!$user) {
            $result->message = 'mustberegistered';
            $result->success = false;
            return $result;
        }

        $favoriteRecord = $this->favouritesRepository->findOneBy([
            'user' => $this->security->getUser(),
            'product' => $product
        ]);

        if (is_null($favoriteRecord)) {
            $favoriteRecord = new Favourites; //add
            $favoriteRecord->setUser($user);
            $favoriteRecord->setProduct($product);
            $favoriteRecord->setDate(new \DateTime());
            $this->em->persist($favoriteRecord);
        } else {
            $this->em->remove($favoriteRecord); //remove like
            $result->isLiked = false;
        }

        $this->em->flush();

        return $result;
    }

    /**
     * @param int $productId
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function checkIsLiked(int $productId): bool
    {
        return $this->favouritesRepository->checkIsLiked($this->security->getUser(), $productId);
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
     * @param int[] $productIds
     * @return string[]
     */
    public function selectLikedProductIds(array $productIds): array
    {
        return $this->favouritesRepository->selectLikedProductIds($this->security->getUser(), $productIds);
    }
}
