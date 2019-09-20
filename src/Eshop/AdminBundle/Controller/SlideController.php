<?php

namespace Eshop\AdminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/", name="admin_slide")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:Slide')->findBy([], ['slideOrder' => 'ASC']);

        return ['entities' => $entities];
    }

    /**
     * Displays a form to create a new Slide entity.
     *
     * @Route("/new", name="admin_slide_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $slide = new Slide();
        $form = $this->createForm('Eshop\ShopBundle\Form\Type\SlideType', $slide);
        $form->add('file', FileType::class, ['required' => true]); //reinit field to make file required
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             if ($form->get('file')->getData() === null){
                 $form->get('file')->addError(new FormError('file is required'));

                 return ['entity' => $slide,
                        'form' => $form->createView()
                 ];
             };

            $em = $this->getDoctrine()->getManager();
            $em->persist($slide);
            $em->flush();

            return $this->redirectToRoute('admin_slide_show', ['id' => $slide->getId()]);
        }

        return ['entity' => $slide,
                'form' => $form->createView()
        ];
    }

    /**
     * Finds and displays a Slide entity.
     *
     * @Route("/{id}", name="admin_slide_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Slide $slide)
    {
        $deleteForm = $this->createDeleteForm($slide);

        return ['entity' => $slide,
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Displays a form to edit an existing Slide entity.
     *
     * @Route("/{id}/edit", name="admin_slide_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Slide $slide)
    {
        $deleteForm = $this->createDeleteForm($slide);
        $editForm = $this->createForm('Eshop\ShopBundle\Form\Type\SlideType', $slide);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($editForm->get('file')->getData() !== null) { // if any file was updated
                $file = $editForm->get('file')->getData();
                $slide->removeUpload(); // remove old file, see this at the bottom
                $slide->setPath(($file->getClientOriginalName())); // set Image Path because preUpload and upload method will not be called if any doctrine entity will not be changed. It tooks me long time to learn it too.
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($slide);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_slide_edit', ['id' => $slide->getId()]);
        }

        return ['entity' => $slide,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Deletes a Slide entity.
     *
     * @Route("/{id}", name="admin_slide_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Slide $slide)
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
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slide $slide)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_slide_delete', ['id' => $slide->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
