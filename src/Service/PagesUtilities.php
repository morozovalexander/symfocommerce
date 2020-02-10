<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OrderProduct;
use App\Entity\Orders;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PagesUtilities
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var ProductRepository */
    private $productRepository;

    /**
     * PagesUtilities constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ) {
        $this->em = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * return sorting name param from request
     *
     * @param Request $request
     * @return string
     */
    public function getSortingParamName(Request $request): string
    {
        $sortParam = $request->get('sort');

        switch ($sortParam) {
            case 'p.name':
                $sortedBy = 'manufacturer.sort.name';
                break;
            case 'p.price':
                $sortedBy = 'manufacturer.sort.price';
                break;
            default:
                $sortedBy = 'manufacturer.sort.default';
                break;
        }
        return $sortedBy;
    }

    /**
     * return last seen products from cookies
     *
     * @param Request $request
     * @return array
     */
    public function getLastSeenProducts(Request $request): array
    {
        $cookies = $request->cookies->all();

        if (isset($cookies['last-seen'])) {
            $productIdsArray = json_decode($cookies['last-seen']);

            if (\is_array($productIdsArray) && !empty($productIdsArray)) {
                return $productIdsArray;
            }
        }
        return [];
    }

    /**
     * Record cart to db order.
     *
     * @param Request $request
     * @param Orders $order
     * @param User|null $user
     * @return bool
     */
    public function createOrderDBRecord(
        Request $request,
        Orders $order,
        ?User $user
    ): bool {
        $cart = $this->getCartFromCookies($request);
        if (!$cart || !\count((array)$cart)) {
            return false;
        }

        //parse cart json form cookies
        $sum = 0; //total control sum of the order
        foreach ($cart as $productId => $productQuantity) {
            $product = $this->productRepository->find((int)$productId);
            if (\is_object($product)) {
                $quantity = abs((int)$productQuantity);
                $sum += ($quantity * $product->getPrice());

                $orderProduct = new OrderProduct();
                $orderProduct->setOrder($order);
                $orderProduct->setProduct($product);
                $orderProduct->setPrice($product->getPrice());
                $orderProduct->setQuantity($quantity);
                $this->em->persist($orderProduct);

                $order->addOrderProduct($orderProduct);
            }
        }

        $order->setUser($user); //can be null if not registered
        $order->setSum($sum);
        $this->em->persist($order);
        $this->em->flush();

        $this->clearCart();
        return true;
    }

    /**
     * clear cookies cart
     *
     * @return void
     */
    public function clearCart(): void
    {
        $response = new Response();
        $response->headers->clearCookie('cart');
        $response->sendHeaders();
    }

    /**
     * Get cart from cookies and return cart or false.
     *
     * @param Request $request
     * @return mixed|null
     */
    private function getCartFromCookies(Request $request)
    {
        $cookies = $request->cookies->all();

        if (isset($cookies['cart'])) {
            $cart = json_decode($cookies['cart']);

            $cartObj = $cart; //check if cart not empty
            if (!empty($cartObj) && \count((array)$cartObj)) {
                return $cart;
            }
        }

        return null;
    }
}
