<?php

namespace Eshop\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Good;
use Eshop\ShopBundle\Form\GoodType;

/**
 * Good controller.
 *
 * @Route("/admin/good")
 */
class GoodController extends Controller
{

    /**
     * Lists all Good entities.
     *
     * @Route("/", name="admin_good")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $dql = "SELECT a FROM ShopBundle:Good a";
        $query = $em->createQuery($dql);
        $limit = $this->getParameter('admin_goods_pagination_count');

        $goods = $paginator->paginate(
            $query,
            $this->get('request')->query->getInt('page', 1),
            $limit
        );

        return array(
            'entities' => $goods,
        );
    }
    /**
     * Creates a new Good entity.
     *
     * @Route("/", name="admin_good_create")
     * @Method("POST")
     * @Template("AdminBundle:Good:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $imageIdString = $request->request->get('filenames');

        $good = new Good();
        $form = $this->createCreateForm($good);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //update uploaded images entities
            if ($imageIdString != ''){
                $imageIdArray = explode(',', $imageIdString);
                array_pop($imageIdArray);

                $imageRepository = $em->getRepository('ShopBundle:Image');
                foreach($imageIdArray as $imageId){
                    $image = $imageRepository->find($imageId);
                    $image->setGood($good);
                    $good->addImage($image);
                    $em->persist($image);
                }
            }

            $em->persist($good);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_good_show', array('id' => $good->getId())));
        }

        return array(
            'entity' => $good,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Good entity.
     *
     * @param Good $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Good $entity)
    {
        $form = $this->createForm(new GoodType(), $entity, array(
            'action' => $this->generateUrl('admin_good_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Good entity.
     *
     * @Route("/new", name="admin_good_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Good();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Good entity.
     *
     * @Route("/{id}", name="admin_good_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Good')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Good entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Good entity.
     *
     * @Route("/{id}/edit", name="admin_good_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Good')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Good entity.');
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
    * Creates a form to edit a Good entity.
    *
    * @param Good $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Good $entity)
    {
        $form = $this->createForm(new GoodType(), $entity, array(
            'action' => $this->generateUrl('admin_good_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Good entity.
     *
     * @Route("/update/{id}", name="admin_good_update")
     * @Method("POST")
     * @Template("AdminBundle:Good:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $imageIdString = $request->request->get('filenames');
        $good = $em->getRepository('ShopBundle:Good')->find($id);

        if (!$good) {
            throw $this->createNotFoundException('Unable to find Good entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($good);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            //add uploaded images entities
            if ($imageIdString != ''){
                $imageIdArray = explode(',', $imageIdString);
                array_pop($imageIdArray);

                $imageRepository = $em->getRepository('ShopBundle:Image');
                foreach($imageIdArray as $imageId){
                    $image = $imageRepository->find($imageId);
                    $image->setGood($good);
                    $good->addImage($image);
                    $em->persist($image);
                }
            }

            $em->flush();

            //add notification
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirect($this->generateUrl('admin_good_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $good,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Good entity.
     *
     * @Route("/{id}", name="admin_good_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ShopBundle:Good')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Good entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_good'));
    }

    /**
     * Creates a form to delete a Good entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_good_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
