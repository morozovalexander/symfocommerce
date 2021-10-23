<?php

namespace App\Controller;

use App\Service\Catalog;
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
     * @param Catalog $catalog
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function favourites(
        Request $request,
        Catalog $catalog,
        PagesUtilities $pagesUtilities
    ): Response {
        $products = $catalog->getFavouriteProducts(
            $this->getParameter('products_pagination_count'),
            $request->query->getInt('page', 1)
        );

        return $this->render('profile/favourites.html.twig', [
            'products' => $products,
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }
}
