<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Entity\Featured;
use Eshop\ShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("/admin/featured")
 */
class FeaturedController extends Controller
{
    /**
     * show featured products
     *
     * @Route("/", name="admin_featured")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $featuredRepository = $em->getRepository('ShopBundle:Featured');
        $products = $featuredRepository->findBy([], ['productOrder' => 'ASC']);

        return ['products' => $products];
    }

    /**
     * @param Request $request
     * @Route("/featured_product_edit_ajax", name="admin_featured_product_edit_ajax")
     * @Method("POST")
     * @return JsonResponse
     */
    public function featuredProductEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');

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
     * @Route("/featured_order_edit_ajax", name="admin_featured_order_edit_ajax")
     * @Method("POST")
     * @return JsonResponse
     */
    public function featuredOrderEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $featuredRepository = $em->getRepository('ShopBundle:Featured');

        $featuredId = $request->request->getInt('featured_id');
        $newOrder = $request->request->getInt('new_value');

        $featuredWithNewOrder = $featuredRepository->findOneBy(['productOrder' => $newOrder]);
        if (is_object($featuredWithNewOrder)) {
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
     * @param bool $addFeaturedValue
     * @return void
     */
    private function createOrDeleteFeaturedProduct($product, $addFeaturedValue)
    {
        $em = $this->getDoctrine()->getManager();
        $featuredRepository = $em->getRepository('ShopBundle:Featured');

        if ($addFeaturedValue) {
            $alreadyFeatured = $product->getFeatured(); //check if already featured

            if (!is_object($alreadyFeatured)) {
                $order = $featuredRepository->getLatestProductOrder(); //create new featured entity

                if (is_array($order)) {
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
    private function returnErrorJson($message) {
        return new JsonResponse([
            'success' => false,
            'message' => $message
        ], 400);
    }
}
