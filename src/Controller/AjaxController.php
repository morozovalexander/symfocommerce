<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\FavouritesRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use App\Entity\Favourites;
use App\Service\PagesUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends AbstractController
{
    /**
     * Lists all Category entities.
     *
     * @Route("/ajax_like", methods={"POST"}, name="ajax_like")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param FavouritesRepository $favouritesRepository
     * @return JsonResponse
     * @throws \Exception
     */
    public function like(
        Request $request,
        ProductRepository $productRepository,
        FavouritesRepository $favouritesRepository
    ): JsonResponse {
        $em = $this->getDoctrine()->getManager();

        $productId = $request->request->getInt('product_id');

        $product = $productRepository->find($productId);
        $user = $this->getUser();

        if (!\is_object($product)) {
            return $this->returnErrorJson('productnotfound');
        }

        if (!\is_object($user)) {
            return $this->returnErrorJson('mustberegistered');
        }

        $favoriteRecord = $favouritesRepository->findOneBy([
            'user' => $this->getUser(),
            'product' => $product
        ]);

        $liked = false;
        if (!\is_object($favoriteRecord)) {
            $favoriteRecord = new Favourites; //add like
            $favoriteRecord->setUser($this->getUser());
            $favoriteRecord->setProduct($product);
            $favoriteRecord->setDate(new \DateTime());
            $em->persist($favoriteRecord);
            $liked = true;
        } else {
            $em->remove($favoriteRecord); //remove like
        }

        $em->flush();

        return new JsonResponse([
            'favourite' => $liked,
            'success' => true
        ], 200);
    }

    /**
     * Checks if user liked this project.
     *
     * @Route("/ajax_is_liked_product", methods={"POST"}, name="ajax_is_liked_product")
     * @param Request $request
     * @param FavouritesRepository $favouritesRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function checkIsLiked(
        Request $request,
        FavouritesRepository $favouritesRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->returnErrorJson('mustberegistered');
        }

        $productId = $request->request->getInt('product_id');

        $liked = $favouritesRepository->checkIsLiked($user, $productId);

        return new JsonResponse([
            'liked' => $liked,
            'success' => true
        ], 200);
    }

    /**
     * Render last seen products from cookies
     *
     * @Route("/ajax_get_last_seen_products", methods={"POST"}, name="ajax_get_last_seen_products")
     * @param Request $request
     * @param PagesUtilities $pagesUtilities
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function getLastSeenProducts(
        Request $request,
        PagesUtilities $pagesUtilities,
        ProductRepository $productRepository
    ): JsonResponse {
        $productIdsArray = $pagesUtilities->getLastSeenProducts($request);

        $products = $productRepository->getLastSeen($productIdsArray, $this->getUser(), 4);
        if (!$products) {
            $this->returnErrorJson('product not forund');
        }
        $html = $this->renderView('_partials/last_seen_products.html.twig', [
                'products' => $products]
        );

        return new JsonResponse([
            'html' => $html,
            'success' => true
        ], 200);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    private function returnErrorJson($message): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message
        ], 400);
    }
}
