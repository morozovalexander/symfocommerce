<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Entity\OrderProduct;
use Eshop\ShopBundle\Entity\Product;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Eshop\ShopBundle\Entity\Orders;

/**
 * Orders controller.
 *
 * @Route("/admin/orders")
 */
class OrdersController extends Controller
{
    /**
     * Lists all Orders entities.
     *
     * @Route("/", methods={"GET"}, name="admin_orders")
     */
    public function indexAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ordersRepository = $em->getRepository('ShopBundle:Orders');
        $paginator = $this->get('knp_paginator');

        $qb = $ordersRepository->getAllOrdersAdminQB();
        $limit = $this->getParameter('admin_manufacturers_pagination_count');

        $orders = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * Finds and displays a Orders entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_order_show")
     */
    public function showAction($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var Orders $order
         */
        //todo: find order automatically and get as parameter
        $order = $em->getRepository('ShopBundle:Orders')->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $orderProducts = $order->getOrderProducts();
        $productsArray = [];

        foreach ($orderProducts as $orderProduct) {
            $productPosition = [];
            /**
             * @var Product $product
             * @var OrderProduct $orderProduct
             */
            $product = $orderProduct->getProduct();
            $price = $orderProduct->getPrice();
            $quantity = $orderProduct->getQuantity();
            $sum = $price * $quantity;

            $productPosition['product'] = $product;
            $productPosition['quantity'] = $quantity;
            $productPosition['price'] = $price;
            $productPosition['sum'] = $sum;

            $productsArray[] = $productPosition;
        }

        return $this->render('admin/orders/show.html.twig', [
            'order' => $order,
            'delete_form' => $deleteForm->createView(),
            'products' => $productsArray
        ]);
    }


    /**
     * Deletes a Orders entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_order_delete")
     */
    public function deleteAction(Request $request, $id): Response
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ShopBundle:Orders')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Order entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_orders'));
    }

    /**
     * Creates a form to delete a Orders entity by id.
     *
     * @param mixed $id The entity id
     * @return FormInterface
     */
    private function createDeleteForm($id): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_order_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
