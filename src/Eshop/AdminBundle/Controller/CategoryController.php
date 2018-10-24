<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Category;

/**
 * Category controller.
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", methods={"GET"}, name="admin_category")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('ShopBundle:Category');
        $paginator = $this->get('knp_paginator');

        $qb = $categoryRepository->getAllCategoriesAdminQB();
        $limit = $this->getParameter('admin_categories_pagination_count');

        $categories = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return ['entities' => $categories];
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_category_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('Eshop\ShopBundle\Form\Type\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_show', ['id' => $category->getId()]);
        }

        return ['entity' => $category,
                'form' => $form->createView()];
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_category_show")
     * @Template()
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return ['entity' => $category,
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_category_edit")
     * @Template()
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm(CategoryType::class, $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }

        return ['entity' => $category,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_category_delete")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_category'));
    }

    /**
     * Creates a form to delete a Category entity by id.
     *
     * @param Category $category The Category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', ['id' => $category->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
