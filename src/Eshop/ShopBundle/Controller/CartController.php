<?php

namespace Eshop\ShopBundle\Controller;

use Eshop\ShopBundle\Entity\Product;
use Eshop\ShopBundle\Entity\Orders;
use Eshop\ShopBundle\Form\Type\OrdersType;
use Eshop\ShopBundle\Service\EmailNotifier;
use Eshop\ShopBundle\Service\PagesUtilities;
use Eshop\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\Error;

class CartController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/showcart", methods={"GET"}, name="showcart")
     * @param Request $request
     * @return Response
     */
    public function showCartAction(Request $request): Response
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
            if (\is_object($product)) {
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

        return $this->render('shop/cart/show_cart.html.twig', [
            'products' => $productsArray,
            'totalsum' => $totalSum
        ]);
    }

    /**
     * Shows order form.
     *
     * @Route("/orderform", methods={"GET", "POST"}, name="orderform")
     * @param Request $request
     * @return Response
     * @throws Error
     */
    public function orderFormAction(Request $request): Response
    {
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderSuccess = $this->get(PagesUtilities::class)->createOrderDBRecord($request, $order, $this->getUser());

            if (!$orderSuccess) {
                return $this->redirect($this->generateUrl('cartisempty')); //check valid cart
            }

            //send email notification
            $this->get(EmailNotifier::class)->handleNotification([
                'event' => 'new_order',
                'order_id' => $order->getId(),
                'admin_email' => $this->getParameter('admin_email')
            ]);

            return $this->render('shop/cart/thank_you.html.twig'); //redirect to thankyou page
        }

        if (\is_object($user = $this->getUser())) {
            $this->fillWithUserData($user, $form);
        }

        return $this->render('shop/cart/order_form.html.twig', [
            'order' => $order,
            'form' => $form->createView()
        ]);
    }

    /**
     * If cart is empty.
     *
     * @Route("/cartisempty", methods={"GET"}, name="cartisempty")
     */
    public function cartIsEmptyAction(): Response
    {
        return $this->render('shop/cart/cart_is_empty.html.twig');
    }

    /**
     * Count cart from cookies
     * @Route("navbar_cart", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function navbarCartAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        //quantity -> sum array
        $cartArray = ['cart' => ['quantity' => 0, 'sum' => 0]];
        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);
            if ($cart === '') {
                return $this->render('shop/cart/navbar_cart.html.twig', $cartArray);
            }
        } else {
            return $this->render('shop/cart/navbar_cart.html.twig', $cartArray);
        }

        $productRepository = $em->getRepository('ShopBundle:Product');

        foreach ($cart as $productId => $productQuantity) {
            /**
             * @var Product $product
             */
            $product = $productRepository->find((int)$productId);
            if (\is_object($product)) {
                $cartArray['cart']['sum'] += ($product->getPrice() * abs((int)$productQuantity));
                $cartArray['cart']['quantity'] += abs((int)$productQuantity);
            }
        }

        return $this->render('shop/cart/navbar_cart.html.twig', $cartArray);
    }

    /**
     * @param User $user
     * @param FormInterface $form
     * @return void
     */
    private function fillWithUserData(User $user, FormInterface $form): void
    {
        $form->get('name')->setData($user->getFirstname() . ' ' . $user->getLastname());
        $form->get('email')->setData($user->getEmail());
        $form->get('phone')->setData($user->getPhone());
        $form->get('address')->setData($user->getAddress());
    }
}
