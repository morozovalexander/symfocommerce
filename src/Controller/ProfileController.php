<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
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
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function favouritesAction(Request $request, ProductRepository $productRepository): Response
    {
        $paginator = $this->get('knp_paginator');
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
