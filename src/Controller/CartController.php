<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Orders;
use App\Form\Type\OrdersType;
use App\Repository\ProductRepository;
use App\Service\EmailNotifier;
use App\Service\PagesUtilities;
use App\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\Error;

class CartController extends AbstractController
{
    /**
     * Lists all Category entities.
     *
     * @Route("/showcart", methods={"GET"}, name="showcart")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function showCartAction(Request $request, ProductRepository $productRepository): Response
    {
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

        return $this->render('cart/show_cart.html.twig', [
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
     * @throws ORMException
     * @throws OptimisticLockException
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
    public function cartIsEmptyAction(): Response
    {
        return $this->render('cart/cart_is_empty.html.twig');
    }

    /**
     * Count cart from cookies
     * @Route("navbar_cart", methods={"GET"})
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function navbarCartAction(Request $request, ProductRepository $productRepository): Response
    {
        //quantity -> sum array
        $cartArray = ['cart' => ['quantity' => 0, 'sum' => 0]];
        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);
            if ($cart === '') {
                return $this->render('cart/navbar_cart.html.twig', $cartArray);
            }
        } else {
            return $this->render('cart/navbar_cart.html.twig', $cartArray);
        }

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

        return $this->render('cart/navbar_cart.html.twig', $cartArray);
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
