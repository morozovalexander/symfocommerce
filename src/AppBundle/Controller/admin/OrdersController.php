<?php

namespace AppBundle\Controller\admin;

use AppBundle\Entity\OrderProduct;
use AppBundle\Entity\Product;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Orders;

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
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ordersRepository = $em->getRepository(Orders::class);
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
     * @param int $id
     * @return Response
     */
    public function showAction(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Orders $order */
        $order = $em->getRepository(Orders::class)->find($id);

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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function deleteAction(Request $request, int $id): Response
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository(Orders::class)->find($id);

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
     * @param int $id The entity id
     * @return FormInterface
     */
    private function createDeleteForm(int $id): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_order_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
