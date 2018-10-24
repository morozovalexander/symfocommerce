<?php

namespace Eshop\ShopBundle\Controller;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Category;
use Eshop\ShopBundle\Entity\Manufacturer;
use Eshop\ShopBundle\Entity\Product;
use Eshop\ShopBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", methods={"GET"}, name="index_main")
     * @Template()
     */
    public function indexAction()
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

        return ['featured_products' => $featuredProducts,
                'latest_products' => $latestProducts,
                'news' => $lastNews,
                'slides' => $slides
        ];
    }

    /**
     * @Route("/category/{slug}", methods={"GET"}, name="category")
     * @Template()
     */
    public function categoryAction(Request $request, Category $category)
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

        return ['category' => $category,
                'products' => $products,
                'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ];
    }

    /**
     * @Route("/manufacturer/{slug}", methods={"GET"}, name="manufacturer")
     * @Template()
     */
    public function manufacturerAction(Request $request, Manufacturer $manufacturer)
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

        return ['manufacturer' => $manufacturer,
                'products' => $products,
                'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ];
    }

    /**
     * @Route("/product/{slug}", methods={"GET"}, name="show_product")
     * @Template()
     */
    public function showProductAction(Product $product)
    {
        return ['product' => $product];
    }

    /**
     * Lists news entities.
     *
     * @Route("/news", methods={"GET"}, name="news")
     * @Template()
     */
    public function newsAction(Request $request)
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

        return ['news' => $news];
    }

    /**
     * search product by title or description
     *
     * @Route("/search", methods={"GET"}, name="search")
     * @Template()
     */
    public function searchProductAction(Request $request)
    {
        $searchResults = [];

        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository('ShopBundle:Product');

        $search_phrase = 'search';
        if ($request->getMethod() == 'GET') {
            $search_phrase = trim($request->get('search_phrase'));
            $searchWords = explode(' ', $search_phrase);

            $qb = $productRepository->getSearchQB($searchWords, $this->getUser());

            $limit = $this->getParameter('search_pagination_count');
            $searchResults = $paginator->paginate(
                $qb,
                $request->query->getInt('page', 1),
                $limit
            );
        }
        return ['products' => $searchResults,
                'search_phrase' => $search_phrase,
                'sortedby' => $this->get('app.page_utilities')->getSortingParamName($request)
        ];
    }

    /**
     * Shows static page.
     *
     * @Route("/{slug}.html", methods={"GET"}, name="show_static_page")
     * @Template()
     */
    public function showStaticPageAction(StaticPage $page)
    {
        return ['page' => $page];
    }
}
