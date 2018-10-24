<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\NewsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\News;

/**
 * News controller.
 *
 * @Route("/admin_news")
 */
class NewsController extends Controller
{
    /**
     * Lists all News entities.
     *
     * @Route("/", methods={"GET"}, name="admin_news")
     * @Template()
     */
    public function indexAction(Request $request)
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

        return ['entities' => $news];
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_news_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('admin_news_show', ['id' => $news->getId()]);
        }

        return ['entity' => $news,
                'form' => $form->createView()
        ];
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_news_show")
     * @Template()
     */
    public function showAction(News $news)
    {
        $deleteForm = $this->createDeleteForm($news);

        return ['entity' => $news,
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_news_edit")
     * @Template()
     */
    public function editAction(Request $request, News $news)
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

            return $this->redirectToRoute('admin_news_edit', ['id' => $news->getId()]);
        }

        return ['entity' => $news,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Deletes a News entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_news_delete")
     */
    public function deleteAction(Request $request, News $news)
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
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(News $news)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_news_delete', ['id' => $news->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
