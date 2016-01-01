<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\StaticPageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\StaticPage;

/**
 * StaticPage controller.
 *
 * @Route("/admin/staticpage")
 */
class StaticPageController extends Controller
{

    /**
     * Lists all StaticPage entities.
     *
     * @Route("/", name="admin_staticpage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ShopBundle:StaticPage')->findBy(array(), array('orderNum' => 'ASC'));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new StaticPage entity.
     *
     * @Route("/", name="admin_staticpage_create")
     * @Method("POST")
     * @Template("AdminBundle:StaticPage:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new StaticPage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_staticpage_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a StaticPage entity.
     *
     * @param StaticPage $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(StaticPage $entity)
    {
        $form = $this->createForm(new StaticPageType(), $entity, array(
            'action' => $this->generateUrl('admin_staticpage_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new StaticPage entity.
     *
     * @Route("/new", name="admin_staticpage_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new StaticPage();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a StaticPage entity.
     *
     * @Route("/{id}", name="admin_staticpage_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:StaticPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticPage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing StaticPage entity.
     *
     * @Route("/{id}/edit", name="admin_staticpage_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:StaticPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticPage entity.');
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
    * Creates a form to edit a StaticPage entity.
    *
    * @param StaticPage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(StaticPage $entity)
    {
        $form = $this->createForm(new StaticPageType(), $entity, array(
            'action' => $this->generateUrl('admin_staticpage_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing StaticPage entity.
     *
     * @Route("/{id}", name="admin_staticpage_update")
     * @Method("PUT")
     * @Template("AdminBundle:StaticPage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:StaticPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticPage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirect($this->generateUrl('admin_staticpage_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a StaticPage entity.
     *
     * @Route("/{id}", name="admin_staticpage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ShopBundle:StaticPage')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find StaticPage entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_staticpage'));
    }

    /**
     * Creates a form to delete a StaticPage entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_staticpage_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
