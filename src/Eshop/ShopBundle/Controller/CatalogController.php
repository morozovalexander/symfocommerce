<?php

namespace Eshop\ShopBundle\Controller;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Category;
use Eshop\ShopBundle\Entity\Manufacturer;
use Eshop\ShopBundle\Entity\Product;
use Eshop\ShopBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", methods={"GET"}, name="index_main")
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository('ShopBundle:News');
        $slideRepository = $em->getRepository('ShopBundle:Slide');
        $productRepository = $em->getRepository('ShopBundle:Product');

        //sorted by order number
        $slides = $slideRepository->findBy(['enabled' => true], ['slideOrder' => 'ASC']);
        $lastNews = $newsRepository->getLastNews();
        $latestProducts = $productRepository->getLatest(12, $this->getUser());
        $featuredProducts = $productRepository->getFeatured(12, $this->getUser());

        return $this->render('shop/catalog/index.html.twig', [
            'featured_products' => $featuredProducts,
            'latest_products' => $latestProducts,
            'news' => $lastNews,
            'slides' => $slides
        ]);
    }

    /**
     * @Route("/category/{slug}", methods={"GET"}, name="category")
     */
    public function categoryAction(Request $request, Category $category): Response
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository('ShopBundle:Product');

        $productsQuery = $productRepository->findByCategoryQB($category, $this->getUser());
        $limit = $this->getParameter('category_products_pagination_count');
        $products = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('shop/catalog/category.html.twig', [
            'category' => $category,
            'products' => $products,
            'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ]);
    }

    /**
     * @Route("/manufacturer/{slug}", methods={"GET"}, name="manufacturer")
     */
    public function manufacturerAction(Request $request, Manufacturer $manufacturer): Response
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository('ShopBundle:Product');

        $productsQuery = $productRepository->findByManufacturerQB($manufacturer, $this->getUser());
        $limit = $this->getParameter('category_products_pagination_count');
        $products = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('shop/catalog/manufacturer.html.twig', [
            'manufacturer' => $manufacturer,
            'products' => $products,
            'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ]);
    }

    /**
     * @Route("/product/{slug}", methods={"GET"}, name="show_product")
     */
    public function showProductAction(Product $product): Response
    {
        return $this->render('shop/catalog/show_product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Lists news entities.
     *
     * @Route("/news", methods={"GET"}, name="news")
     */
    public function newsAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $newsRepository = $em->getRepository('ShopBundle:News');
        $limit = $this->getParameter('products_pagination_count');

        $query = $newsRepository->getNewsQB();

        $news = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('shop/catalog/news.html.twig', [
            'news' => $news
        ]);
    }

    /**
     * search product by title or description
     *
     * @Route("/search", methods={"GET"}, name="search")
     */
    public function searchProductAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository('ShopBundle:Product');

        $search_phrase = trim($request->get('search_phrase'));
        $searchWords = explode(' ', $search_phrase);

        $qb = $productRepository->getSearchQB($searchWords, $this->getUser());

        $limit = $this->getParameter('search_pagination_count');
        $searchResults = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('shop/catalog/search_product.html.twig', [
            'products' => $searchResults,
            'search_phrase' => $search_phrase,
            'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ]);
    }

    /**
     * Shows static page.
     *
     * @Route("/{slug}.html", methods={"GET"}, name="show_static_page")
     */
    public function showStaticPageAction(StaticPage $page): Response
    {
        return $this->render('shop/catalog/show_static_page.html.twig', [
            'page' => $page
        ]);
    }
}
