<?php

namespace Eshop\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Orders;
use Eshop\ShopBundle\Form\OrdersType;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $dql = "SELECT a FROM ShopBundle:Orders a ORDER BY a.date DESC";
        $query = $em->createQuery($dql);
        $limit = $this->getParameter('admin_manufacturers_pagination_count');

        $orders = $paginator->paginate(
            $query,
            $this->get('request')->query->getInt('page', 1),
            $limit
        );

        return array(
            'entities' => $orders,
        );
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

        $entity = $em->getRepository('ShopBundle:Orders')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
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
            ->setAction($this->generateUrl('admin_order_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Delete',
                'attr' => array('onclick' => 'return confirm("Are you sure?")')
            ))
            ->getForm()
            ;
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