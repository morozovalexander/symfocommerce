<?php

namespace Eshop\ShopBundle\Controller;

use Eshop\ShopBundle\Entity\Product;
use Eshop\ShopBundle\Entity\Orders;
use Eshop\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/showcart", methods={"GET"}, name="showcart")
     * @Template()
     */
    public function showCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $productsArray = [];
        $cart = [];
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
                $productPosition = [];

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

        return ['products' => $productsArray,
                'totalsum' => $totalSum
        ];
    }

    /**
     * Shows order form.
     *
     * @Route("/orderform", methods={"GET", "POST"}, name="orderform")
     * @Template()
     */
    public function orderFormAction(Request $request)
    {
        $order = new Orders();
        $form = $this->createForm('Eshop\ShopBundle\Form\Type\OrdersType', $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderSuccess = $this->get('app.page_utilities')->createOrderDBRecord($request, $order, $this->getUser());

            if (!$orderSuccess) {
                return $this->redirect($this->generateUrl('cartisempty')); //check valid cart
            }

            //send email notification
            $this->get('app.email_notifier')->handleNotification([
                'event' => 'new_order',
                'order_id' => $order->getId(),
                'admin_email' => $this->getParameter('admin_email')
            ]);

            return $this->render('@Shop/Cart/thank_you.html.twig'); //redirect to thankyou page
        }

        if (is_object($user = $this->getUser())) {
            $this->fillWithUserData($user, $form);
        }

        return [
            'order' => $order,
            'form' => $form->createView()
        ];
    }

    /**
     * If cart is empty.
     *
     * @Route("/cartisempty", methods={"GET"}, name="cartisempty")
     * @Template()
     */
    public function cartIsEmptyAction()
    {
        return [];
    }

    /**
     * Count cart from cookies
     * @Route("navbar_cart", methods={"GET"})
     * @Template()
     */
    public function navbarCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //quantity -> sum array
        $cartArray = ['cart' => ['quantity' => 0, 'sum' => 0]];
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
