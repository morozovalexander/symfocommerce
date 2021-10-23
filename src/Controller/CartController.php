<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\Type\OrdersType;
use App\Service\Cart;
use App\Service\EmailNotifier;
use App\Service\PagesUtilities;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\Error;

class CartController extends AbstractController
{
    /**
     * @Route("/showcart", methods={"GET"}, name="showcart")
     * @param Request $request
     * @param Cart $cart
     * @return Response
     */
    public function showCart(Request $request, Cart $cart): Response
    {
        $cookies = $request->cookies->all();
        $quantityByProductId = isset($cookies['cart']) ? json_decode($cookies['cart'], true) : [];
        $cartContents = $cart->getCartContents($quantityByProductId);

        return $this->render('cart/show_cart.html.twig', [
            'products' => $cartContents->positions,
            'totalSum' => $cartContents->totalSum
        ]);
    }

    /**
     * Shows order form.
     *
     * @Route("/orderform", methods={"GET", "POST"}, name="orderform")
     * @param Request $request
     * @param PagesUtilities $pagesUtilities
     * @param EmailNotifier $emailNotifier
     * @return Response
     * @throws Error
     */
    public function orderForm(
        Request $request,
        PagesUtilities $pagesUtilities,
        EmailNotifier $emailNotifier
    ): Response {
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderSuccess = $pagesUtilities->createOrderDBRecord($request, $order, $this->getUser());

            if (!$orderSuccess) {
                return $this->redirect($this->generateUrl('cartisempty')); //check valid cart
            }

            //send email notification
            $emailNotifier->handleNotification([
                'event' => 'new_order',
                'order_id' => $order->getId(),
                'admin_email' => $this->getParameter('admin_email')
            ]);

            return $this->render('cart/thank_you.html.twig'); //redirect to thankyou page
        }

        if (\is_object($user = $this->getUser())) {
            $this->fillWithUserData($user, $form);
        }

        return $this->render('cart/order_form.html.twig', [
            'order' => $order,
            'form' => $form->createView()
        ]);
    }

    /**
     * If cart is empty.
     *
     * @Route("/cartisempty", methods={"GET"}, name="cartisempty")
     */
    public function cartIsEmpty(): Response
    {
        return $this->render('cart/cart_is_empty.html.twig');
    }

    /**
     * @Route("navbar_cart", methods={"GET"})
     * @param Request $request
     * @param Cart $cart
     * @return Response
     */
    public function navbarCart(Request $request, Cart $cart): Response
    {
        $cookies = $request->cookies->all();
        $quantityByProductId = isset($cookies['cart']) ? json_decode($cookies['cart'], true) : [];
        $cartContents = $cart->getCartContents($quantityByProductId);
        return $this->render('cart/navbar_cart.html.twig', [
            'productsCount' => count($cartContents->positions),
            'totalSum' => $cartContents->totalSum
        ]);
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
