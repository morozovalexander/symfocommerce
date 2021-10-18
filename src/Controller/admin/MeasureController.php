<?php

namespace App\Controller\admin;

use App\Form\Type\MeasureType;
use App\Repository\MeasureRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Measure;

/**
 * Measure controller.
 *
 * @Route("/admin/measure")
 */
class MeasureController extends AbstractController
{
    /**
     * Lists all Measure entities.
     *
     * @Route("/", methods={"GET"}, name="admin_measure")
     * @param MeasureRepository $measureRepository
     * @return Response
     */
    public function index(MeasureRepository $measureRepository): Response
    {
        return $this->render('admin/measure/index.html.twig', [
            'entities' => $measureRepository->findAll()
        ]);
    }

    /**
     * Displays a form to create a new Measure entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_measure_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $measure = new Measure();
        $form = $this->createForm(MeasureType::class, $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            return $this->redirectToRoute('admin_measure_show', [
                'id' => $measure->getId()
            ]);
        }

        return $this->render('admin/measure/new.html.twig', [
            'entity' => $measure,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a Measure entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_measure_show")
     * @param Measure $measure
     * @return Response
     */
    public function show(Measure $measure): Response
    {
        $deleteForm = $this->createDeleteForm($measure);

        return $this->render('admin/measure/show.html.twig', [
            'entity' => $measure,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing Measure entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_measure_edit")
     * @param Request $request
     * @param Measure $measure
     * @return Response
     */
    public function edit(Request $request, Measure $measure): Response
    {
        $deleteForm = $this->createDeleteForm($measure);
        $editForm = $this->createForm(MeasureType::class, $measure);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_measure_edit', [
                'id' => $measure->getId()
            ]);
        }

        return $this->render('admin/measure/edit.html.twig', [
            'entity' => $measure,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a Measure entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_measure_delete")
     * @param Request $request
     * @param Measure $measure
     * @return Response
     */
    public function delete(Request $request, Measure $measure): Response
    {
        $form = $this->createDeleteForm($measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($measure);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_measure'));
    }

    /**
     * Creates a form to delete a Measure entity by id.
     *
     * @param Measure $measure The measure entity
     * @return FormInterface
     */
    private function createDeleteForm(Measure $measure): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_measure_delete', ['id' => $measure->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
