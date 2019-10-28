<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\NewsType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Eshop\ShopBundle\Entity\News;

/**
 * News controller.
 *
 * @Route("/admin/news")
 */
class NewsController extends Controller
{
    /**
     * Lists all News entities.
     *
     * @Route("/", methods={"GET"}, name="admin_news")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository('ShopBundle:News');
        $paginator = $this->get('knp_paginator');

        $qb = $newsRepository->getAllNewsAdminQB();
        $limit = $this->getParameter('admin_categories_pagination_count');

        $news = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('admin/news/index.html.twig', [
            'entities' => $news
        ]);
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_news_new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('admin_news_show', [
                'id' => $news->getId()
            ]);
        }

        return $this->render(':admin/news:new.html.twig', [
            'entity' => $news,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_news_show")
     * @param News $news
     * @return Response
     */
    public function showAction(News $news): Response
    {
        $deleteForm = $this->createDeleteForm($news);

        return $this->render('admin/news/show.html.twig', [
            'entity' => $news,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_news_edit")
     * @param Request $request
     * @param News $news
     * @return Response
     */
    public function editAction(Request $request, News $news): Response
    {
        $deleteForm = $this->createDeleteForm($news);
        $editForm = $this->createForm(NewsType::class, $news);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_news_edit', [
                'id' => $news->getId()
            ]);
        }

        return $this->render('admin/news/edit.html.twig', [
            'entity' => $news,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a News entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_news_delete")
     * @param Request $request
     * @param News $news
     * @return Response
     */
    public function deleteAction(Request $request, News $news): Response
    {
        $form = $this->createDeleteForm($news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($news);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_news'));
    }

    /**
     * Creates a form to delete a News entity.
     *
     * @param News $news The News entity
     * @return FormInterface
     */
    private function createDeleteForm(News $news): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_news_delete', ['id' => $news->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
