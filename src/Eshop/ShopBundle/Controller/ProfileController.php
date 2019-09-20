<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * Display favourite products.
     *
     * @Route("/favourites", name="favourites")
     * @Method("GET")
     * @Template()
     */
    public function favouritesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository('ShopBundle:Product');
        $limit = $this->getParameter('products_pagination_count');

        $query = $productRepository->getFavouritesQB($this->getUser());

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );

        return ['products' => $products,
                'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ];
    }
}
