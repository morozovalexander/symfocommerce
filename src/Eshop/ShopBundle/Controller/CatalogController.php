<?php

namespace Eshop\ShopBundle\Controller;

use Eshop\ShopBundle\Entity\Category;
use Eshop\ShopBundle\Entity\Manufacturer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CatalogController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="index_main")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('ShopBundle:Category');

        $categories = $categoryRepository->findAll();

        return array(
            'categories' => $categories
        );
    }

    /**
     * @Template()
     */
    public function categoriesMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('ShopBundle:Category');

        $categories = $categoryRepository->findAll();

        return array(
            'categories' => $categories
        );
    }

    /**
     * @Template()
     */
    public function manufacturersMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');

        $manufacturers = $manufacturerRepository->findAll();

        return array(
            'manufacturers' => $manufacturers
        );
    }

    /**
     * @Route("/category/{categoryId}", name="category")
     * @Method("GET")
     * @Template()
     */
    public function categoryAction($categoryId = '')
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $categoryRepository = $em->getRepository('ShopBundle:Category');
        $goodRepository = $em->getRepository('ShopBundle:Good');

        /**
         * @var Category $requiredCategory
         */
        if ($categoryId == '') {
            //get first category id
            $requiredCategory = $categoryRepository->getFirstCategoryId();
            $requiredCategory = $requiredCategory['id'];
        } else {
            $requiredCategory = $categoryRepository->find((int)$categoryId);
            $requiredCategory = $requiredCategory->getId();
        }

        $goodsQuery = $goodRepository->findByCategoryForPaginator($requiredCategory);
        $limit = $this->getParameter('category_goods_pagination_count');
        $goods = $paginator->paginate(
            $goodsQuery,
            $this->get('request')->query->getInt('page', 1),
            $limit
        );

        $category = $categoryRepository->find($requiredCategory);

        return array(
            'category' => $category,
            'goods' => $goods
        );
    }

    /**
     * @Route("/manufacturer/{manufacturerId}", name="manufacturer")
     * @Method("GET")
     * @Template()
     */
    public function manufacturerAction($manufacturerId = '')
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');
        $goodRepository = $em->getRepository('ShopBundle:Good');

        /**
         * @var Manufacturer $requiredManufacturer
         */
        if ($manufacturerId == '') {
            //get first category id
            $requiredManufacturer = $manufacturerRepository->getFirstManufacturerId();
            $requiredManufacturer = $requiredManufacturer['id'];
        } else {
            $requiredManufacturer = $manufacturerRepository->find((int)$manufacturerId);
            $requiredManufacturer = $requiredManufacturer->getId();
        }

        $goodsQuery = $goodRepository->findByManufacturerForPaginator($requiredManufacturer);
        $limit = $this->getParameter('category_goods_pagination_count');
        $goods = $paginator->paginate(
            $goodsQuery,
            $this->get('request')->query->getInt('page', 1),
            $limit
        );

        $manufacturer = $manufacturerRepository->find($requiredManufacturer);

        return array(
            'manufacturer' => $manufacturer,
            'goods' => $goods
        );
    }
}
