<?php

namespace AppBundle\Controller\admin;

use AppBundle\Form\Type\StaticPageType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\StaticPage;

/**
 * StaticPage controller.
 *
 * @Route("/admin/staticpage")
 */
class StaticPageController extends AbstractController
{
    /**
     * Lists all StaticPage entities.
     *
     * @Route("/", methods={"GET"}, name="admin_staticpage")
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository(StaticPage::class)->findBy([], ['orderNum' => 'ASC']);

        return $this->render('admin/static_page/index.html.twig', [
            'entities' => $entities
        ]);
    }

    /**
     * Displays a form to create a new StaticPage entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_staticpage_new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $staticPage = new StaticPage();
        $form = $this->createForm(StaticPageType::class, $staticPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($staticPage);
            $em->flush();

            return $this->redirectToRoute('admin_staticpage_show', [
                'id' => $staticPage->getId()
            ]);
        }

        return $this->render('admin/static_page/new.html.twig', [
            'entity' => $staticPage,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a StaticPage entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_staticpage_show")
     * @param StaticPage $staticPage
     * @return Response
     */
    public function showAction(StaticPage $staticPage): Response
    {
        $deleteForm = $this->createDeleteForm($staticPage);

        return $this->render('admin/static_page/show.html.twig', [
            'entity' => $staticPage,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing StaticPage entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_staticpage_edit")
     * @param Request $request
     * @param StaticPage $staticPage
     * @return Response
     */
    public function editAction(Request $request, StaticPage $staticPage): Response
    {
        $deleteForm = $this->createDeleteForm($staticPage);
        $editForm = $this->createForm(StaticPageType::class, $staticPage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($staticPage);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_staticpage_edit', [
                'id' => $staticPage->getId()
            ]);
        }

        return $this->render('admin/static_page/edit.html.twig', [
            'entity' => $staticPage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a StaticPage entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_staticpage_delete")
     * @param Request $request
     * @param StaticPage $staticPage
     * @return Response
     */
    public function deleteAction(Request $request, StaticPage $staticPage): Response
    {
        $form = $this->createDeleteForm($staticPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($staticPage);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_staticpage'));
    }

    /**
     * Creates a form to delete a StaticPage entity by id.
     *
     * @param StaticPage $staticPage The StaticPage entity
     * @return FormInterface
     */
    private function createDeleteForm(StaticPage $staticPage): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_staticpage_delete', ['id' => $staticPage->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
