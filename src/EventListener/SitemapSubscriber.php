<?php

namespace App\EventListener;

use App\Repository\CategoryRepository;
use App\Repository\ManufacturerRepository;
use App\Repository\ProductRepository;
use App\Repository\StaticPageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriber implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents(): array
    {
        return [SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate'];
    }

    /**
     * @param SitemapPopulateEvent $event
     * @throws \Exception
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerIndexPage($event->getUrlContainer());
        $this->registerManufacturersUrls($event->getUrlContainer());
        $this->registerCategoriesUrls($event->getUrlContainer());
        $this->registerNewsUrls($event->getUrlContainer());
        $this->registerProductsUrls($event->getUrlContainer());
        $this->registerStaticPagesUrls($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     * @throws \Exception
     */
    public function registerIndexPage(UrlContainerInterface $urls): void
    {
        $frequency = UrlConcrete::CHANGEFREQ_WEEKLY;
        $priority = 1;

        $urls->addUrl(
            new UrlConcrete(
                $this->urlGenerator->generate('index_main', [], UrlGeneratorInterface::ABSOLUTE_URL),
                new \DateTime(),
                $frequency,
                $priority
            ),
            'homepage'
        );
    }

    /**
     * @param UrlContainerInterface $urls
     * @param ManufacturerRepository $manufacturerRepository
     */
    public function registerManufacturersUrls(
        UrlContainerInterface $urls,
        ManufacturerRepository $manufacturerRepository
    ): void {
        $manufacturers = $manufacturerRepository->getArrayForSitemap();
        $frequency = UrlConcrete::CHANGEFREQ_MONTHLY;
        $priority = 1;

        foreach ($manufacturers as $manufacturer) {
            $url = $this->urlGenerator->generate(
                'manufacturer',
                ['slug' => $manufacturer['slug']],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $urls->addUrl(
                new UrlConcrete(
                    $url,
                    $manufacturer['dateUpdated'],
                    $frequency,
                    $priority
                ),
                'manufacturers'
            );
        }
    }

    /**
     * @param UrlContainerInterface $urls
     * @param CategoryRepository $categoryRepository
     */
    public function registerCategoriesUrls(UrlContainerInterface $urls, CategoryRepository $categoryRepository): void
    {
        $categories = $categoryRepository->getArrayForSitemap();
        $frequency = UrlConcrete::CHANGEFREQ_MONTHLY;
        $priority = 1;

        foreach ($categories as $category) {
            $url = $this->urlGenerator->generate(
                'category',
                ['slug' => $category['slug']],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $urls->addUrl(
                new UrlConcrete(
                    $url,
                    $category['dateUpdated'],
                    $frequency,
                    $priority
                ),
                'categories'
            );
        }
    }

    /**
     * @param UrlContainerInterface $urls
     * @param ProductRepository $productRepository
     */
    public function registerProductsUrls(UrlContainerInterface $urls, ProductRepository $productRepository): void
    {
        $products = $productRepository->getArrayForSitemap();
        $frequency = UrlConcrete::CHANGEFREQ_MONTHLY;
        $priority = 0.7;

        foreach ($products as $product) {
            $url = $this->urlGenerator->generate(
                'show_product',
                ['slug' => $product['slug']],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $urls->addUrl(
                new UrlConcrete(
                    $url,
                    $product['dateUpdated'],
                    $frequency,
                    $priority
                ),
                'products'
            );
        }
    }

    /**
     * @param UrlContainerInterface $urls
     * @throws \Exception
     */
    public function registerNewsUrls(UrlContainerInterface $urls): void
    {
        $frequency = UrlConcrete::CHANGEFREQ_WEEKLY;
        $priority = 1;

        $urls->addUrl(
            new UrlConcrete(
                $this->urlGenerator->generate(
                    'news', [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                new \DateTime(),
                $frequency,
                $priority
            ),
            'news'
        );
    }

    /**
     * @param UrlContainerInterface $urls
     * @param StaticPageRepository $staticPageRepository
     * @throws \Exception
     */
    public function registerStaticPagesUrls(
        UrlContainerInterface $urls,
        StaticPageRepository $staticPageRepository
    ): void {
        $staticPages = $staticPageRepository->getArrayForSitemap();
        $frequency = UrlConcrete::CHANGEFREQ_MONTHLY;
        $priority = 0.7;
        $date = new \DateTime();

        foreach ($staticPages as $staticPage) {
            $url = $this->urlGenerator->generate(
                'show_static_page',
                ['slug' => $staticPage['slug']],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $urls->addUrl(
                new UrlConcrete(
                    $url,
                    $date,
                    $frequency,
                    $priority
                ),
                'pages'
            );
        }
    }
}
