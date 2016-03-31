<?php

namespace Eshop\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Product;
use Eshop\ShopBundle\Form\Type\ProductType;

/**
 * Product controller.
 *
 * @Route("/admin/product")
 */
class ProductController extends Controller
{

    /**
     * Lists all Product entities.
     *
     * @Route("/", name="admin_product")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $paginator = $this->get('knp_paginator');

        $qb = $productRepository->getAllProductsAdminQB();
        $limit = $this->getParameter('admin_products_pagination_count');

        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return array(
            'entities' => $products,
        );
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/", name="admin_product_create")
     * @Method("POST")
     * @Template("AdminBundle:Product:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $imageIdString = $request->request->get('filenames');

        $product = new Product();
        $form = $this->createCreateForm($product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //update uploaded images entities
            if ($imageIdString != '') {
                $imageIdArray = explode(',', $imageIdString);
                array_pop($imageIdArray);

                $imageRepository = $em->getRepository('ShopBundle:Image');
                foreach ($imageIdArray as $imageId) {
                    $image = $imageRepository->find($imageId);
                    $image->setProduct($product);
                    $product->addImage($image);
                    $em->persist($image);
                }
            }

            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_product_show', array('id' => $product->getId())));
        }

        return array(
            'entity' => $product,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Product entity.
     *
     * @param Product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('admin_product_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Product entity.
     *
     * @Route("/new", name="admin_product_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Product();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}", name="admin_product_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        if ($entity->getDeleted()) {
            return $this->render('@Admin/Product/deleted.html.twig');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", name="admin_product_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Product entity.
     *
     * @param Product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('admin_product_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Product entity.
     *
     * @Route("/update/{id}", name="admin_product_update")
     * @Method("POST")
     * @Template("AdminBundle:Product:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $imageIdString = $request->request->get('filenames');
        $product = $em->getRepository('ShopBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($product);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            //add uploaded images entities
            if ($imageIdString != '') {
                $imageIdArray = explode(',', $imageIdString);
                array_pop($imageIdArray);

                $imageRepository = $em->getRepository('ShopBundle:Image');
                foreach ($imageIdArray as $imageId) {
                    $image = $imageRepository->find($imageId);
                    $image->setProduct($product);
                    $product->addImage($image);
                    $em->persist($image);
                }
            }

            $em->flush();

            //add notification
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirect($this->generateUrl('admin_product_edit', array('id' => $id)));
        }

        return array(
            'entity' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}", name="admin_product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ShopBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }
            $entity->setDeleted(true);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_product'));
    }

    /**
     * Creates a form to delete a Product entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/remove_image", name="remove_image", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function removeImageAction(Request $request)
    {
        $requestData = $request->request;
        $imageEntityId = (int)$requestData->get('imageEntityId');
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $imageRepository = $em->getRepository('ShopBundle:Image');
        /**
         * @var Image $image , $imageRepository
         */
        $image = $imageRepository->find($imageEntityId);
        $product = $image->getProduct();
        $product->removeImage($image);

        $em->persist($product);
        $em->remove($image);

        $em->flush();

        $data = json_encode(array('success' => true));
        $headers = array('Content-type' => 'application-json; charset=utf8');
        $response = new Response($data, 200, $headers);
        return $response;
    }
}
