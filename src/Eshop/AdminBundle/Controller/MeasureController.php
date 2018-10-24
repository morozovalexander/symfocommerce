<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Form\Type\MeasureType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Measure;

/**
 * Measure controller.
 *
 * @Route("/admin/measure")
 */
class MeasureController extends Controller
{
    /**
     * Lists all Measure entities.
     *
     * @Route("/", methods={"GET"}, name="admin_measure")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ShopBundle:Measure')->findAll();

        return ['entities' => $entities];
    }

    /**
     * Displays a form to create a new Measure entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_measure_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $measure = new Measure();
        $form = $this->createForm(MeasureType::class, $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            return $this->redirectToRoute('admin_measure_show', ['id' => $measure->getId()]);
        }

        return ['entity' => $measure,
                'form' => $form->createView()
        ];
    }

    /**
     * Finds and displays a Measure entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_measure_show")
     * @Template()
     */
    public function showAction(Measure $measure)
    {
        $deleteForm = $this->createDeleteForm($measure);

        return ['entity' => $measure,
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Displays a form to edit an existing Measure entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_measure_edit")
     * @Template()
     */
    public function editAction(Request $request, Measure $measure)
    {
        $deleteForm = $this->createDeleteForm($measure);
        $editForm = $this->createForm('Eshop\ShopBundle\Form\Type\MeasureType', $measure);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_measure_edit', ['id' => $measure->getId()]);
        }

        return ['entity' => $measure,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Deletes a Measure entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_measure_delete")
     */
    public function deleteAction(Request $request, Measure $measure)
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
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Measure $measure)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_measure_delete', ['id' => $measure->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
