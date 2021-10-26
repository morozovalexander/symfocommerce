<?php

namespace App\Controller;

use App\Service\FavouriteProducts;
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
     * @param FavouriteProducts $favouriteProducts
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function favourites(
        Request $request,
        FavouriteProducts $favouriteProducts,
        PagesUtilities $pagesUtilities
    ): Response {
        $products = $favouriteProducts->getFavouriteProducts(
            $this->getParameter('products_pagination_count'),
            $request->query->getInt('page', 1)
        );

        return $this->render('profile/favourites.html.twig', [
            'products' => $products,
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }
}
