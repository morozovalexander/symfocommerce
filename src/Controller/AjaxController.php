<?php

namespace App\Controller;

use App\Service\Catalog;
use App\Service\FavouriteProducts;
use Doctrine\ORM\NonUniqueResultException;
use App\Service\PagesUtilities;
use Doctrine\ORM\NoResultException;
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
     * @param FavouriteProducts $favouriteProducts
     * @return JsonResponse
     */
    public function like(Request $request, FavouriteProducts $favouriteProducts): JsonResponse
    {
        $result = $favouriteProducts->toggleProductLike(
            $request->request->getInt('product_id')
        );

        return new JsonResponse([
            'favourite' => $result->isLiked,
            'success' => $result->success,
            'message' => $result->message
        ], $result->success ? 200 : 400);
    }

    /**
     * Checks if user liked this project.
     *
     * @Route("/ajax_is_liked_product", methods={"POST"}, name="ajax_is_liked_product")
     * @param Request $request
     * @param FavouriteProducts $favouriteProducts
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function checkIsLiked(Request $request, FavouriteProducts $favouriteProducts): JsonResponse
    {
        if (!$this->getUser()) {
            return $this->returnErrorJson('mustberegistered');
        }

        return new JsonResponse([
            'liked' => $favouriteProducts->checkIsLiked($request->request->getInt('product_id')),
            'success' => true
        ], 200);
    }

    /**
     * Return liked products ids from sent array
     * @Route("/ajax_check_liked_products", methods={"POST"}, name="ajax_check_liked_products")
     * @param Request $request
     * @param FavouriteProducts $favouriteProducts
     * @return JsonResponse
     */
    public function checkLikedProducts(Request $request, FavouriteProducts $favouriteProducts): JsonResponse
    {
        $productIds = array_map('intval', $request->request->get('product_ids', []));

        return new JsonResponse([
            'liked' => $favouriteProducts->selectLikedProductIds($productIds),
            'success' => true
        ], 200);
    }

    /**
     * Render last seen products from cookies
     *
     * @Route("/ajax_get_last_seen_products", methods={"POST"}, name="ajax_get_last_seen_products")
     * @param Request $request
     * @param PagesUtilities $pagesUtilities
     * @param Catalog $catalog
     * @return JsonResponse
     */
    public function getLastSeenProducts(
        Request $request,
        PagesUtilities $pagesUtilities,
        Catalog $catalog
    ): JsonResponse {
        $productIds = $pagesUtilities->getLastSeenProducts($request);
        $products = $catalog->getLastSeenProducts($productIds);

        if (!count($products)) {
            $this->returnErrorJson('product not found');
        }
        $html = $this->renderView('_partials/last_seen_products.html.twig', [
            'products' => $products
        ]);

        return new JsonResponse([
            'html' => $html,
            'success' => true
        ], 200);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    private function returnErrorJson(string $message): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message
        ], 400);
    }
}
