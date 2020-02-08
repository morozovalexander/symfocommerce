<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\StaticPage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param ManagerRegistry $doctrine
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ManagerRegistry $doctrine)
    {
        $this->urlGenerator = $urlGenerator;
        $this->doctrine = $doctrine;
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
     */
    public function registerManufacturersUrls(UrlContainerInterface $urls): void
    {
        $manufacturers = $this->doctrine->getRepository(Manufacturer::class)->getArrayForSitemap();
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
     */
    public function registerCategoriesUrls(UrlContainerInterface $urls): void
    {
        $categories = $this->doctrine->getRepository(Category::class)->getArrayForSitemap();
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
     */
    public function registerProductsUrls(UrlContainerInterface $urls): void
    {
        $products = $this->doctrine->getRepository(Product::class)->getArrayForSitemap();
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
     * @throws \Exception
     */
    public function registerStaticPagesUrls(UrlContainerInterface $urls): void
    {
        $staticPages = $this->doctrine->getRepository(StaticPage::class)->getArrayForSitemap();
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
