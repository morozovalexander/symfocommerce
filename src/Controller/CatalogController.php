<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use App\Repository\ProductRepository;
use App\Repository\SlideRepository;
use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\StaticPage;
use App\Service\PagesUtilities;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends AbstractController
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", methods={"GET"}, name="index_main")
     * @param NewsRepository $newsRepository
     * @param SlideRepository $slideRepository
     * @param ProductRepository $productRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(
        NewsRepository $newsRepository,
        SlideRepository $slideRepository,
        ProductRepository $productRepository
    ): Response {
        //sorted by order number
        $slides = $slideRepository->findBy(['enabled' => true], ['slideOrder' => 'ASC']);
        $lastNews = $newsRepository->getLastNews();
        $latestProducts = $productRepository->getLatest(12, $this->getUser());
        $featuredProducts = $productRepository->getFeatured(12, $this->getUser());

        return $this->render('catalog/index.html.twig', [
            'featured_products' => $featuredProducts,
            'latest_products' => $latestProducts,
            'news' => $lastNews,
            'slides' => $slides
        ]);
    }

    /**
     * @Route("/category/{slug}", methods={"GET"}, name="category")
     * @param Request $request
     * @param Category $category
     * @param ProductRepository $productRepository
     * @param PaginatorInterface $paginator
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function category(
        Request $request,
        Category $category,
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        PagesUtilities $pagesUtilities
    ): Response {
        $productsQuery = $productRepository->findByCategoryQB($category, $this->getUser());
        $limit = $this->getParameter('category_products_pagination_count');
        $products = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('catalog/category.html.twig', [
            'category' => $category,
            'products' => $products,
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }

    /**
     * @Route("/manufacturer/{slug}", methods={"GET"}, name="manufacturer")
     * @param Request $request
     * @param Manufacturer $manufacturer
     * @param ProductRepository $productRepository
     * @param PaginatorInterface $paginator
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function manufacturer(
        Request $request,
        Manufacturer $manufacturer,
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        PagesUtilities $pagesUtilities
    ): Response {
        $productsQuery = $productRepository->findByManufacturerQB($manufacturer, $this->getUser());
        $limit = $this->getParameter('category_products_pagination_count');
        $products = $paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('catalog/manufacturer.html.twig', [
            'manufacturer' => $manufacturer,
            'products' => $products,
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }

    /**
     * @Route("/product/{slug}", methods={"GET"}, name="show_product")
     * @param Product $product
     * @return Response
     */
    public function showProduct(Product $product): Response
    {
        return $this->render('catalog/show_product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Lists news entities.
     *
     * @Route("/news", methods={"GET"}, name="news")
     * @param Request $request
     * @param NewsRepository $newsRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function news(
        Request $request,
        NewsRepository $newsRepository,
        PaginatorInterface $paginator
    ): Response {
        $limit = $this->getParameter('products_pagination_count');

        $query = $newsRepository->getNewsQB();

        $news = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('catalog/news.html.twig', [
            'news' => $news
        ]);
    }

    /**
     * search product by title or description
     *
     * @Route("/search", methods={"GET"}, name="search")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param PaginatorInterface $paginator
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function searchProduct(
        Request $request,
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        PagesUtilities $pagesUtilities
    ): Response {
        $search_phrase = trim($request->get('search_phrase'));
        $searchWords = explode(' ', $search_phrase);

        $qb = $productRepository->getSearchQB($searchWords, $this->getUser());

        $limit = $this->getParameter('search_pagination_count');
        $searchResults = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('catalog/search_product.html.twig', [
            'products' => $searchResults,
            'search_phrase' => $search_phrase,
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }

    /**
     * Shows static page.
     *
     * @Route("/{slug}.html", methods={"GET"}, name="show_static_page")
     * @param StaticPage $page
     * @return Response
     */
    public function showStaticPage(StaticPage $page): Response
    {
        return $this->render('catalog/show_static_page.html.twig', [
            'page' => $page
        ]);
    }
}
