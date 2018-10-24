<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\ManufacturerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/", methods={"GET"}, name="admin_manufacturer")
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

        return ['entities' => $manufacturers];
    }

    /**
     * Displays a form to create a new Manufacturer entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_manufacturer_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            return $this->redirectToRoute('admin_manufacturer_show', ['id' => $manufacturer->getId()]);
        }

        return ['entity' => $manufacturer,
                'form' => $form->createView()
        ];
    }

    /**
     * Finds and displays a Manufacturer entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_manufacturer_show")
     * @Template()
     */
    public function showAction(Manufacturer $manufacturer)
    {
        $deleteForm = $this->createDeleteForm($manufacturer);

        return ['entity' => $manufacturer,
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Displays a form to edit an existing Manufacturer entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_manufacturer_edit")
     * @Template()
     */
    public function editAction(Request $request, Manufacturer $manufacturer)
    {
        $deleteForm = $this->createDeleteForm($manufacturer);
        $editForm = $this->createForm('Eshop\ShopBundle\Form\Type\ManufacturerType', $manufacturer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_manufacturer_edit', ['id' => $manufacturer->getId()]);
        }

        return ['entity' => $manufacturer,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Deletes a Manufacturer entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_manufacturer_delete")
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
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Manufacturer $manufacturer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_manufacturer_delete', ['id' => $manufacturer->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
