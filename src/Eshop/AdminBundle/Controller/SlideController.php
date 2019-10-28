<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\SlideType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Eshop\ShopBundle\Entity\Slide;

/**
 * Slide controller.
 *
 * @Route("/admin/slide")
 */
class SlideController extends Controller
{
    /**
     * Lists all Slide entities.
     *
     * @Route("/", methods={"GET"}, name="admin_slide")
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:Slide')->findBy([], ['slideOrder' => 'ASC']);

        return $this->render('admin/slide/index.html.twig', [
            'entities' => $entities
        ]);
    }

    /**
     * Displays a form to create a new Slide entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_slide_new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $slide = new Slide();
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData() === null) {
                $form->get('image')->addError(new FormError('file is required'));

                // todo: review later
                return $this->render('admin/slide/new.html.twig', [
                    'entity' => $slide,
                    'form' => $form->createView()
                ]);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($slide);
            $em->flush();

            return $this->redirectToRoute('admin_slide_show', [
                    'id' => $slide->getId()]
            );
        }

        return $this->render('admin/slide/new.html.twig', [
            'entity' => $slide,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a Slide entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_slide_show")
     * @param Slide $slide
     * @return Response
     */
    public function showAction(Slide $slide): Response
    {
        $deleteForm = $this->createDeleteForm($slide);

        return $this->render('admin/slide/show.html.twig', [
            'entity' => $slide,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing Slide entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_slide_edit")
     */
    public function editAction(Request $request, Slide $slide): Response
    {
        $deleteForm = $this->createDeleteForm($slide);
        $editForm = $this->createForm(SlideType::class, $slide);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slide);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_slide_edit', ['id' => $slide->getId()]);
        }

        return $this->render('admin/slide/edit.html.twig', [
            'entity' => $slide,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a Slide entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_slide_delete")
     * @param Request $request
     * @param Slide $slide
     * @return Response
     */
    public function deleteAction(Request $request, Slide $slide): Response
    {
        $form = $this->createDeleteForm($slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slide);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_slide'));
    }

    /**
     * Creates a form to delete a Slide entity by id.
     *
     * @param Slide $slide The Slide entity
     * @return FormInterface
     */
    private function createDeleteForm(Slide $slide): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_slide_delete', ['id' => $slide->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
