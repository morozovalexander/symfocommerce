<?php

namespace Eshop\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Image;
use Eshop\ShopBundle\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Product;

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
     * @Route("/", methods={"GET"}, name="admin_product")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $paginator = $this->get('knp_paginator');

        //if search is required
        $searchWords = trim($request->get('search_words'));

        $qb = $productRepository->searchProductsAdminQB($searchWords);
        $limit = $this->getParameter('admin_products_pagination_count');

        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return ['products' => $products,
                'search_words' => $searchWords
        ];
    }

    /**
     * Displays a form to create a new Product entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_product_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //update uploaded images entities
            $imageIdString = $request->request->get('filenames');
            if ($imageIdString != '') {
                $imageIdArray = explode(',', $imageIdString);
                array_pop($imageIdArray);

                $em = $this->getDoctrine()->getManager();
                $imageRepository = $em->getRepository('ShopBundle:Image');
                foreach ($imageIdArray as $imageId) {
                    $image = $imageRepository->find($imageId);
                    $image->setProduct($product);
                    $product->addImage($image);
                    $em->persist($image);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_product_show', ['id' => $product->getId()]);
        }

        return ['entity' => $product,
                'form' => $form->createView()
        ];
    }

    /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}", methods={"GET"}, name="admin_product_show")
     * @Template()
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        if ($product->getDeleted()) {
            return $this->render('@Admin/Product/deleted.html.twig');
        }

        return ['entity' => $product,
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_product_edit")
     * @Template()
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //update uploaded images entities
            $imageIdString = $request->request->get('filenames');
            if ($imageIdString != '') {
                $imageIdArray = explode(',', $imageIdString);
                array_pop($imageIdArray);

                $em = $this->getDoctrine()->getManager();
                $imageRepository = $em->getRepository('ShopBundle:Image');
                foreach ($imageIdArray as $imageId) {
                    $image = $imageRepository->find($imageId);
                    $image->setProduct($product);
                    $product->addImage($image);
                    $em->persist($image);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        return ['entity' => $product,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
        ];
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}", methods={"DELETE"}, name="admin_product_delete")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_product'));
    }

    /**
     * @Route("/remove_image", methods={"POST"} , name="remove_image", defaults={"_format"="json"})
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

        $data = json_encode(['success' => true]);
        $headers = ['Content-type' => 'application-json; charset=utf8'];
        return new Response($data, 200, $headers);
    }

    /**
     * Creates a form to delete a Product entity by id.
     *
     * @param Product $product The Product id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', ['id' => $product->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
