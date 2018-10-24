<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\CategoryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     */
    public function indexAction(Request $request): Response
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

        return $this->render('admin/category/index.html.twig', [
                'entities' => $categories]
        );
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_category_new")
     */
    public function newAction(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_show', ['id' => $category->getId()]);
        }

        return $this->render('admin/category/new.html.twig', [
            'entity' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_category_show")
     */
    public function showAction(Category $category): Response
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('admin/category/show.html.twig', [
            'entity' => $category,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_category_edit")
     */
    public function editAction(Request $request, Category $category): Response
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

            return $this->redirectToRoute('admin_category_edit', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('admin/category/edit.html.twig', [
            'entity' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_category_delete")
     */
    public function deleteAction(Request $request, Category $category): Response
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
     * @return FormInterface
     */
    private function createDeleteForm(Category $category): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', ['id' => $category->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
