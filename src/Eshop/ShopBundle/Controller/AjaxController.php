<?php

namespace Eshop\ShopBundle\Controller;

use Doctrine\ORM\NonUniqueResultException;
use Eshop\ShopBundle\Entity\Favourites;
use Eshop\ShopBundle\Service\PagesUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/ajax_like", methods={"POST"}, name="ajax_like")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function likeAction(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $favouritesRepository = $em->getRepository('ShopBundle:Favourites');

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
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function checkIsLikedAction(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $favouritesRepository = $em->getRepository('ShopBundle:Favourites');
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
     * @return JsonResponse
     */
    public function getLastSeenProductsAction(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');

        $productIdsArray = $this->get(PagesUtilities::class)->getLastSeenProducts($request);

        $products = $productRepository->getLastSeen($productIdsArray, $this->getUser(), 4);
        if (!$products) {
            $this->returnErrorJson('product not forund');
        }
        $html = $this->renderView('shop/_partials/last_seen_products.html.twig', [
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
