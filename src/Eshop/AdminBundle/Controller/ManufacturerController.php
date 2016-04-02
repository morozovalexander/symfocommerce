<?php

namespace Eshop\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Manufacturer;

/**
 * Manufacturer controller.
 *
 * @Route("/admin/manufacturer")
 */
class ManufacturerController extends Controller
{
    /**
     * Lists all Manufacturer entities.
     *
     * @Route("/", name="admin_manufacturer")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');
        $paginator = $this->get('knp_paginator');

        $qb = $manufacturerRepository->getAllManufacturersAdminQB();
        $limit = $this->getParameter('admin_manufacturers_pagination_count');

        $manufacturers = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return array(
            'entities' => $manufacturers,
        );
    }

    /**
     * Displays a form to create a new Manufacturer entity.
     *
     * @Route("/new", name="admin_manufacturer_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm('Eshop\ShopBundle\Form\Type\ManufacturerType', $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            return $this->redirectToRoute('admin_manufacturer_show', array('id' => $manufacturer->getId()));
        }

        return array(
            'entity' => $manufacturer,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Manufacturer entity.
     *
     * @Route("/{id}", name="admin_manufacturer_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Manufacturer $manufacturer)
    {
        $deleteForm = $this->createDeleteForm($manufacturer);

        return array(
            'entity' => $manufacturer,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Manufacturer entity.
     *
     * @Route("/{id}/edit", name="admin_manufacturer_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Manufacturer $manufacturer)
    {
        $deleteForm = $this->createDeleteForm($manufacturer);
        $editForm = $this->createForm('Eshop\ShopBundle\Form\Type\ManufacturerType', $manufacturer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($editForm->get('file')->getData() !== null) { // if any file was updated
                $file = $editForm->get('file')->getData();
                $manufacturer->removeUpload(); // remove old file, see this at the bottom
                $manufacturer->setPath(($file->getClientOriginalName())); // set Image Path because preUpload and upload method will not be called if any doctrine entity will not be changed. It tooks me long time to learn it too.
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_manufacturer_edit', array('id' => $manufacturer->getId()));
        }

        return array(
            'entity' => $manufacturer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Manufacturer entity.
     *
     * @Route("/{id}", name="admin_manufacturer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Manufacturer $manufacturer)
    {
        $form = $this->createDeleteForm($manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($manufacturer);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_manufacturer'));
    }

    /**
     * Creates a form to delete a Manufacturer entity by id.
     *
     * @param Manufacturer $manufacturer The Manufacturer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Manufacturer $manufacturer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_manufacturer_delete', array('id' => $manufacturer->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
