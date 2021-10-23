<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\StaticPage;
use App\Service\Catalog;
use App\Service\News;
use App\Service\PagesUtilities;
use App\Service\Slides;
use Doctrine\ORM\NonUniqueResultException;
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
     * @param Slides $slides
     * @param News $news
     * @param Catalog $catalog
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(Slides $slides, News $news, Catalog $catalog): Response
    {
        return $this->render('catalog/index.html.twig', [
            'featured_products' => $catalog->getFeaturedProducts(),
            'latest_products' => $catalog->getLatestProducts(),
            'news' => $news->getLastNews(),
            'slides' => $slides->getSlides()
        ]);
    }

    /**
     * @Route("/category/{slug}", methods={"GET"}, name="category")
     * @param Request $request
     * @param Category $category
     * @param Catalog $catalog
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function category(
        Request $request,
        Category $category,
        Catalog $catalog,
        PagesUtilities $pagesUtilities
    ): Response {
        $productsLimit = $this->getParameter('category_products_pagination_count');
        $productsPage = $request->query->getInt('page', 1);
        return $this->render('catalog/category.html.twig', [
            'category' => $category,
            'products' => $catalog->getProductsByCategory($category, $productsLimit, $productsPage),
            'sortedby' => $pagesUtilities->getSortingParamName($request)
        ]);
    }

    /**
     * @Route("/manufacturer/{slug}", methods={"GET"}, name="manufacturer")
     * @param Request $request
     * @param Manufacturer $manufacturer
     * @param Catalog $catalog
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function manufacturer(
        Request $request,
        Manufacturer $manufacturer,
        Catalog $catalog,
        PagesUtilities $pagesUtilities
    ): Response {
        $productsLimit = $this->getParameter('manufacturer_products_pagination_count');
        $productsPage = $request->query->getInt('page', 1);
        return $this->render('catalog/manufacturer.html.twig', [
            'manufacturer' => $manufacturer,
            'products' => $catalog->getProductsByManufacturer($manufacturer, $productsLimit, $productsPage),
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
     * @param News $news
     * @return Response
     */
    public function news(Request $request, News $news): Response {
        return $this->render('catalog/news.html.twig', [
            'news' => $news->getNews(
                $this->getParameter('news_pagination_count'),
                $request->query->getInt('page', 1)
            )
        ]);
    }

    /**
     * search product by title or description
     *
     * @Route("/search", methods={"GET"}, name="search")
     * @param Request $request
     * @param Catalog $catalog
     * @param PagesUtilities $pagesUtilities
     * @return Response
     */
    public function searchProduct(Request $request, Catalog $catalog, PagesUtilities $pagesUtilities): Response
    {
        $searchPhrase = $request->get('search_phrase');
        $searchResults = $catalog->searchProduct(
            $this->getParameter('search_pagination_count'),
            $request->query->getInt('page', 1),
            $searchPhrase
        );

        return $this->render('catalog/search_product.html.twig', [
            'products' => $searchResults,
            'search_phrase' => $searchPhrase,
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
