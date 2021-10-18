<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\PagesUtilities;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param PaginatorInterface $paginator
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function favourites(
        Request $request,
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        PagesUtilities $pagesUtilities
    ): Response {
        $limit = $this->getParameter('products_pagination_count');
        $query = $productRepository->getFavouritesQB($this->getUser());

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('profile/favourites.html.twig', [
            'products' => $products,
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }
}
