<?php

namespace App\Service;

use App\Entity\Favourites;
use App\Repository\FavouritesRepository;
use App\Repository\ProductRepository;
use App\Service\Structs\ProductLikeResult;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    /**
     * @param EntityManagerInterface $em
     * @param ProductRepository $productRepository
     * @param FavouritesRepository $favouritesRepository
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $productRepository,
        FavouritesRepository $favouritesRepository,
        Security $security
    ) {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->favouritesRepository = $favouritesRepository;
        $this->security = $security;
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

        if (!$favoriteRecord) {
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
}
