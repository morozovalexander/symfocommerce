<?php

namespace Eshop\ShopBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Eshop\ShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/showcart", name="showcart")
     * @Method("GET")
     * @Template()
     */
    public function showCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $productsArray = array();
        $totalSum = 0;

        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);
            if ($cart == '') {
                return array();
            }
        } else {
            return array();
        }


        foreach ($cart as $productId => $productQuantity) {
            /**
             * @var Product $product
             */
            $product = $productRepository->find((int)$productId);
            if (is_object($product)) {
                $productPosition = array();

                $quantity = abs((int)$productQuantity);
                $price = $product->getPrice();
                $sum = $price * $quantity;

                $productPosition['product'] = $product;
                $productPosition['quantity'] = $quantity;
                $productPosition['price'] = $price;
                $productPosition['sum'] = $sum;
                $totalSum += $sum;

                $productsArray[] = $productPosition;
            }
        }


        return array(
            'products' => $productsArray,
            'totalsum' => $totalSum
        );
    }

    /**
     * Count cart from cookies
     *
     * @Method("GET")
     * @Template()
     */
    public function navbarCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //quantity -> sum array
        $cartArray = array('quantity' => 0, 'sum' => 0);
        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);
            if ($cart == '') {
                return $cartArray;
            }
        } else {
            return $cartArray;
        }

        $productRepository = $em->getRepository('ShopBundle:Product');

        foreach ($cart as $productId => $productQuantity) {
            /**
             * @var Product $product
             */
            $product = $productRepository->find((int)$productId);
            if (is_object($product)) {
                $cartArray['sum'] += ($product->getPrice() * abs((int)$productQuantity));
                $cartArray['quantity'] += abs((int)$productQuantity);
            }
        }

        return array(
            'cart' => $cartArray
        );
    }
}
