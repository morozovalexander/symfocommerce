<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Manufacturer;
use Eshop\ShopBundle\Form\ManufacturerType;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ShopBundle:Manufacturer')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Manufacturer entity.
     *
     * @Route("/", name="admin_manufacturer_create")
     * @Method("POST")
     * @Template("ShopBundle:Manufacturer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Manufacturer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_manufacturer_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Manufacturer entity.
     *
     * @param Manufacturer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Manufacturer $entity)
    {
        $form = $this->createForm(new ManufacturerType(), $entity, array(
            'action' => $this->generateUrl('admin_manufacturer_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Manufacturer entity.
     *
     * @Route("/new", name="admin_manufacturer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Manufacturer();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Manufacturer entity.
     *
     * @Route("/{id}", name="admin_manufacturer_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Manufacturer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Manufacturer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Manufacturer entity.
     *
     * @Route("/{id}/edit", name="admin_manufacturer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Manufacturer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Manufacturer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Manufacturer entity.
    *
    * @param Manufacturer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Manufacturer $entity)
    {
        $form = $this->createForm(new ManufacturerType(), $entity, array(
            'action' => $this->generateUrl('admin_manufacturer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Manufacturer entity.
     *
     * @Route("/{id}", name="admin_manufacturer_update")
     * @Method("PUT")
     * @Template("ShopBundle:Manufacturer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Manufacturer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Manufacturer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_manufacturer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Manufacturer entity.
     *
     * @Route("/{id}", name="admin_manufacturer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ShopBundle:Manufacturer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Manufacturer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_manufacturer'));
    }

    /**
     * Creates a form to delete a Manufacturer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_manufacturer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
