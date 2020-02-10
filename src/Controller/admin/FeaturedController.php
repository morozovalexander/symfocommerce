<?php

namespace App\Controller\admin;

use App\Entity\Featured;
use App\Entity\Product;
use App\Repository\FeaturedRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("/admin/featured")
 */
class FeaturedController extends AbstractController
{
    /**
     * show featured products
     *
     * @Route("/", methods={"GET"}, name="admin_featured")
     * @param FeaturedRepository $featuredRepository
     * @return Response
     */
    public function indexAction(FeaturedRepository $featuredRepository): Response
    {
        $products = $featuredRepository->findBy([], ['productOrder' => 'ASC']);

        return $this->render('admin/featured/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @Route("/featured_product_edit_ajax", methods={"POST"}, name="admin_featured_product_edit_ajax")
     */
    public function featuredProductEditAction(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $productId = $request->request->getInt('product_id');
        $addFeaturedValue = $request->request->getBoolean('new_value');

        $product = $productRepository->find($productId);
        if (!$product) {
            return $this->returnErrorJson('product not found');
        }

        $this->createOrDeleteFeaturedProduct($product, $addFeaturedValue);

        return new JsonResponse(['success' => true], 200);
    }

    /**
     * @param Request $request
     * @param FeaturedRepository $featuredRepository
     * @return JsonResponse
     * @Route("/featured_order_edit_ajax", methods={"POST"}, name="admin_featured_order_edit_ajax")
     */
    public function featuredOrderEditAction(Request $request, FeaturedRepository $featuredRepository): JsonResponse
    {
        $featuredId = $request->request->getInt('featured_id');
        $newOrder = $request->request->getInt('new_value');

        $featuredWithNewOrder = $featuredRepository->findOneBy(['productOrder' => $newOrder]);
        if (\is_object($featuredWithNewOrder)) {
            return $this->returnErrorJson('order exists');
        }

        $featured = $featuredRepository->find($featuredId); //search featured record
        if (!$featured) {
            return $this->returnErrorJson('entity not found');
        }

        $featured->setProductOrder($newOrder);
        $em->flush();

        return new JsonResponse(['success' => true], 200);
    }

    /**
     * @param Product $product
     * @param FeaturedRepository $featuredRepository
     * @param bool $addFeaturedValue
     * @return void
     * @throws NonUniqueResultException
     */
    private function createOrDeleteFeaturedProduct(
        Product $product,
        FeaturedRepository $featuredRepository,
        bool $addFeaturedValue): void
    {
        $em = $this->getDoctrine()->getManager();

        if ($addFeaturedValue) {
            $alreadyFeatured = $product->getFeatured(); //check if already featured

            if (!\is_object($alreadyFeatured)) {
                $order = $featuredRepository->getLatestProductOrder(); //create new featured entity

                if (\is_array($order)) {
                    $newOrder = $order['productOrder'] + 1;
                } else {
                    $newOrder = 1;
                }

                $featured = new Featured;
                $featured->setProduct($product);
                $featured->setProductOrder($newOrder);
                $em->persist($featured);
            }
        } else {
            $featured = $featuredRepository->findOneBy(['product' => $product]);
            $em->remove($featured);
        }
        $em->flush();
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
