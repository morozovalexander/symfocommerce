<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CatalogController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $categoryRepository = $em->getRepository('ShopBundle:Category');
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');

        $categories = $categoryRepository->findAll();
        $manufacturers = $manufacturerRepository->findAll();


        $dql = "SELECT a FROM ShopBundle:Good a";
        $query = $em->createQuery($dql);

        $limit = $this->getParameter('goods_pagination_count');
        $goods = $paginator->paginate(
            $query,
            $this->get('request')->query->getInt('page', 1),
            $limit
        );

        return array(
            'goods' => $goods,
            'categories' => $categories,
            'manufacturers' => $manufacturers
        );
    }
}
