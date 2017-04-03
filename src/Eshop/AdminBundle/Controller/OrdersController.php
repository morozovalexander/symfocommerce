<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Entity\OrderProduct;
use Eshop\ShopBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/", name="admin_orders")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
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

        return ['orders' => $orders];
    }

    /**
     * Finds and displays a Orders entity.
     *
     * @Route("/{id}", name="admin_order_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var Orders $order
         */
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

        return ['order' => $order,
                'delete_form' => $deleteForm->createView(),
                'products' => $productsArray
        ];
    }


    /**
     * Creates a form to delete a Orders entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_order_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Deletes a Orders entity.
     *
     * @Route("/{id}", name="admin_order_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
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
}
