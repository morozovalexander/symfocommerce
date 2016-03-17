<?php

namespace Eshop\ShopBundle\Controller;

use Eshop\ShopBundle\Entity\Product;
use Eshop\ShopBundle\Entity\Orders;
use Eshop\ShopBundle\Entity\OrderProduct;
use Eshop\ShopBundle\Form\Type\OrdersType;
use Eshop\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $cart = array();
        $totalSum = 0;

        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);
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
     * Shows order form.
     *
     * @Route("/orderform", name="orderform")
     * @Method("GET")
     * @Template()
     */
    public function orderFormAction(Request $request)
    {
        $order = new Orders();
        $form = $this->createCreateOrderForm($order);

        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $cart = array();
        $totalSum = 0;

        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);
        }

        foreach ($cart as $productId => $productQuantity) {
            /**
             * @var Product $product
             */
            $product = $productRepository->find((int)$productId);
            if (is_object($product)) {

                $quantity = abs((int)$productQuantity);
                $price = $product->getPrice();
                $sum = $price * $quantity;

                $totalSum += $sum;
            }
        }

        if (is_object($user = $this->getUser())) {
            $this->fillWithUserData($user, $form);
        }

        return array(
            'totalsum' => $totalSum,
            'order' => $order,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Orders entity.
     *
     * @Route("/order_create", name="order_create")
     * @Method("POST")
     * @Template("ShopBundle:Cart:orderForm.html.twig")
     */
    public function createOrderAction(Request $request)
    {
        $order = new Orders();
        $form = $this->createCreateOrderForm($order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $productRepository = $em->getRepository('ShopBundle:Product');

            $cart = $this->getCartFromCookies($request);
            if (!$cart) {
                return $this->redirect($this->generateUrl('cartisempty')); //check valid cart
            }

            //parse cart json form cookies
            foreach ($cart as $productId => $productQuantity) {
                $product = $productRepository->find((int)$productId);
                if (is_object($product)) {
                    $quantity = abs((int)$productQuantity);

                    $orderProduct = new OrderProduct();
                    $orderProduct->setProduct($product);
                    $orderProduct->setOrder($order);
                    $orderProduct->setQuantity($quantity);
                    $em->persist($orderProduct);

                    $order->addOrderProduct($orderProduct);
                }
            }

            $order->setUser($this->getUser()); //can be null if not regisered
            $em->persist($order);
            $em->flush();

            $this->get('app.page_utilities')->clearCart();

            //send email notification
            $notifier = $this->get('app.email_notifier');
            $notifier->handleNotification(array(
                'event' => 'new_order',
                'order_id' => $order->getId(),
                'admin_email' => $this->getParameter('admin_email')
            ));

            //redirect to thankyou page
            return $this->redirect($this->generateUrl('thankyou'));
        }

        return array(
            'entity' => $order,
            'form' => $form->createView(),
        );
    }

    /**
     * If cart is empty.
     *
     * @Route("/cartisempty", name="cartisempty")
     * @Method("GET")
     * @Template()
     */
    public function cartIsEmptyAction()
    {
        return array();
    }

    /**
     * If orderForm valid and submitted.
     *
     * @Route("/thankyou", name="thankyou")
     * @Method("GET")
     * @Template()
     */
    public function thankYouAction()
    {
        return array();
    }

    /**
     * Creates a form to create a Orders entity.
     *
     * @param Orders $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateOrderForm(Orders $entity)
    {
        $form = $this->createForm(new OrdersType(), $entity, array(
            'action' => $this->generateUrl('order_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Get cart from cookies and return cart object or false.
     */
    private function getCartFromCookies(Request $request)
    {
        $cookies = $request->cookies->all();
        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);

            //check if cart not empty
            $cartArray = (array)$cart;
            if (!empty($cartArray)) {
                return $cart;
            }
        }
        return false;
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
        $cartArray = array(
            'cart' => array('quantity' => 0, 'sum' => 0)
        );
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
                $cartArray['cart']['sum'] += ($product->getPrice() * abs((int)$productQuantity));
                $cartArray['cart']['quantity'] += abs((int)$productQuantity);
            }
        }

        return $cartArray;
    }

    /**
     * @param User $user
     * @param Form $form
     * @return void
     */
    private function fillWithUserData($user, $form)
    {
        $form->get('name')->setData($user->getFirstname() . ' ' . $user->getLastname());
        $form->get('email')->setData($user->getEmail());
        $form->get('phone')->setData($user->getPhone());
        $form->get('address')->setData($user->getAddress());
    }
}
