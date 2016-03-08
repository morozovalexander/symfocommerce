<?php

namespace Eshop\ShopBundle\Controller;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Category;
use Eshop\ShopBundle\Entity\Manufacturer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="index_main")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('ShopBundle:Category');
        $newsRepository = $em->getRepository('ShopBundle:News');
        $slideRepository = $em->getRepository('ShopBundle:Slide');

        $settings = $this->get('app.site_settings');
        $showEmptyCategories = $settings->getShowEmptyCategories();

        $categories = $categoryRepository->getAllCategories($showEmptyCategories);
        //sorted by order number
        $slides = $slideRepository->findBy(array('enabled' => true), array('slideOrder' => 'ASC'));
        $lastNews = $newsRepository->getLastNews();

        return array(
            'categories' => $categories,
            'news' => $lastNews,
            'slides' => $slides
        );
    }

    /**
     * @Template()
     */
    public function categoriesMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('ShopBundle:Category');

        $settings = $this->get('app.site_settings');
        $showEmpty = $settings->getShowEmptyCategories();

        $categories = $categoryRepository->getAllCategories($showEmpty);

        return array(
            'categories' => $categories
        );
    }

    /**
     * @Template()
     */
    public function manufacturersMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');

        $settings = $this->get('app.site_settings');
        $showEmpty = $settings->getShowEmptyManufacturers();

        $manufacturers = $manufacturerRepository->getAllManufacturers($showEmpty);

        return array(
            'manufacturers' => $manufacturers
        );
    }

    /**
     * @Route("/category/{slug}", name="category")
     * @Method("GET")
     * @Template()
     */
    public function categoryAction(Request $request, $slug = '')
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $categoryRepository = $em->getRepository('ShopBundle:Category');
        $productRepository = $em->getRepository('ShopBundle:Product');
        /**
         * @var Category $requiredCategory
         */
        if ($slug == '') {
            //get first category id
            $requiredCategory = $categoryRepository->getFirstCategoryId();
        } else {
            $requiredCategory = $categoryRepository->findBySlug($slug);
        }

        if (!is_object($requiredCategory)) {
            throw new NotFoundHttpException("Category not found");
        }

        $productsQuery = $productRepository->findByCategoryForPaginator($requiredCategory);
        $limit = $this->getParameter('category_products_pagination_count');
        $products = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            $limit
        );

        return array(
            'category' => $requiredCategory,
            'products' => $products,
            'sortedby' => $this->getSortingParamName($request)
        );
    }

    /**
     * @Route("/manufacturer/{slug}", name="manufacturer")
     * @Method("GET")
     * @Template()
     */
    public function manufacturerAction(Request $request, $slug = '')
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');
        $productRepository = $em->getRepository('ShopBundle:Product');

        /**
         * @var Manufacturer $requiredManufacturer
         */
        if ($slug == '') {
            //get first category id
            $requiredManufacturer = $manufacturerRepository->getFirstManufacturer();
        } else {
            $requiredManufacturer = $manufacturerRepository->findBySlug($slug);
        }

        if (!is_object($requiredManufacturer)) {
            throw new NotFoundHttpException("Manufacturer not found");
        }

        $productsQuery = $productRepository->findByManufacturerForPaginator($requiredManufacturer);
        $limit = $this->getParameter('category_products_pagination_count');
        $products = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            $limit
        );

        return array(
            'manufacturer' => $requiredManufacturer,
            'products' => $products,
            'sortedby' => $this->getSortingParamName($request)
        );
    }

    /**
     * @Route("/product/{slug}", name="show_product")
     * @Method("GET")
     * @Template()
     */
    public function showProductAction($slug = '')
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');

        if ($slug == '') {
            return $this->redirectToRoute('index_main');
        } else {
            $product = $productRepository->findBySlug($slug);
        }

        if (!is_object($product)) {
            throw new NotFoundHttpException("Product not found");
        }

        return array(
            'product' => $product
        );
    }

    /**
     * Lists news entities.
     *
     * @Route("/news", name="news")
     * @Method("GET")
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

        return array(
            'news' => $news
        );
    }

    /**
     * search product by title or description
     *
     * @Route("/search", name="search")
     * @Method("GET")
     * @Template()
     */
    public function searchProductAction(Request $request)
    {
        /**
         * @var $em EntityManager
         */
        $searchResults = array();

        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $productRepository = $em->getRepository('ShopBundle:Product');

        $search_phrase = 'search';
        if ($request->getMethod() == 'GET') {
            $search_phrase = trim($request->get('search_phrase'));
            $searchWords = explode(' ', $search_phrase);

            $qb = $productRepository->getSearchQuery($searchWords);

            $limit = $this->getParameter('search_pagination_count');
            $searchResults = $paginator->paginate(
                $qb,
                $request->query->getInt('page', 1)/*page number*/,
                $limit
            );
        }
        return array(
            'products' => $searchResults,
            'search_phrase' => $search_phrase,
            'sortedby' => $this->getSortingParamName($request)
        );
    }

    /**
     * return sorting name param form request.
     */
    private function getSortingParamName(Request $request)
    {
        $sortedBy = '';
        $sortParam = $request->get('sort');

        switch ($sortParam) {
            case 'p.name':
                $sortedBy = 'manufacturer.sort.name';
                break;
            case 'p.price':
                $sortedBy = 'manufacturer.sort.price';
                break;
            default:
                $sortedBy = 'manufacturer.sort.default';
                break;
        }
        return $sortedBy;
    }

    /**
     * Render static top menu for static pages.
     *
     * @Method("GET")
     * @Template()
     */
    public function staticPagesMenuAction()
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $headers = $em->getRepository('ShopBundle:StaticPage')->getHeaders();

        return array(
            'headers' => $headers
        );
    }

    /**
     * Shows static page.
     *
     * @Route("/{slug}.html",name="show_static_page")
     * @Method("GET")
     * @Template()
     */
    public function showStaticPageAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('ShopBundle:StaticPage')->findBySlug($slug);

        if (!is_object($page)) {
            throw new NotFoundHttpException("Static page not found");
        }

        return array(
            'page' => $page
        );
    }
}
