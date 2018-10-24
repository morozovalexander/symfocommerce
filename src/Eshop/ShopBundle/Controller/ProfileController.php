<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * Display favourite products.
     *
     * @Route("/favourites", methods={"GET"}, name="favourites")
     */
    public function favouritesAction(Request $request): Response
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

        return $this->render('shop/profile/favourites.html.twig', [
            'products' => $products,
            'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ]);
    }
}
