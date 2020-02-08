<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\PagesUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    /**
     * Display favourite products.
     *
     * @Route("/favourites", methods={"GET"}, name="favourites")
     * @param Request $request
     * @return Response
     */
    public function favouritesAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository(Product::class);
        $limit = $this->getParameter('products_pagination_count');

        $query = $productRepository->getFavouritesQB($this->getUser());

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('profile/favourites.html.twig', [
            'products' => $products,
            'sortedby' => $this->get(PagesUtilities::class)->getSortingParamName($request)
        ]);
    }
}
