<?php
namespace Eshop\ShopBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapListener implements SitemapListenerInterface
{
    private $event;
    private $router;
    private $entityManager;

    public function __construct(RouterInterface $router, EntityManager $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $this->event = $event;
        $section = $this->event->getSection();

        if (is_null($section) || $section == 'default') {
            $dateTime = new \DateTime();

            $products = $this->entityManager->getRepository('ShopBundle:Product')->findAll();
            $categories = $this->entityManager->getRepository('ShopBundle:Category')->findAll();
            $manufacturers = $this->entityManager->getRepository('ShopBundle:Manufacturer')->findAll();
            $staticPages = $this->entityManager->getRepository('ShopBundle:StaticPage')->findAll();

            //for homepage
            $url = $this->router->generate('index_main', [], RouterInterface::ABSOLUTE_URL);
            $this->createSitemapEntry($url, $dateTime, UrlConcrete::CHANGEFREQ_WEEKLY, 1);

            //for manufacturers
            foreach ($manufacturers as $manufacturer) {
                $url = $this->router->generate('manufacturer', ['slug' => $manufacturer->getSlug()], RouterInterface::ABSOLUTE_URL);
                $modified = $manufacturer->getDateUpdated();
                $this->createSitemapEntry($url, $modified, UrlConcrete::CHANGEFREQ_MONTHLY, 1);
            }

            //for categories
            foreach ($categories as $category) {
                $url = $this->router->generate('category', ['slug' => $category->getSlug()], RouterInterface::ABSOLUTE_URL);
                $modified = $category->getDateUpdated();
                $this->createSitemapEntry($url, $modified, UrlConcrete::CHANGEFREQ_MONTHLY, 1);
            }

            //for products
            foreach ($products as $product) {
                $url = $this->router->generate('show_product', ['slug' => $product->getSlug()], RouterInterface::ABSOLUTE_URL);
                $modified = $product->getDateUpdated();
                $this->createSitemapEntry($url, $modified, UrlConcrete::CHANGEFREQ_MONTHLY, 0.7);
            }

            //for news
            $url = $this->router->generate('news', [], RouterInterface::ABSOLUTE_URL);
            $this->createSitemapEntry($url, $dateTime, UrlConcrete::CHANGEFREQ_WEEKLY, 1);

            //for staticPages
            foreach ($staticPages as $staticPage) {
                $url = $this->router->generate('show_static_page', ['slug' => $staticPage->getSlug()], RouterInterface::ABSOLUTE_URL);
                $this->createSitemapEntry($url, $dateTime, UrlConcrete::CHANGEFREQ_MONTHLY, 0.7);
            }
        }
    }

    /**
     * @param $url string
     * @param $modifiedDate \DateTime
     * @param $changeFrequency float
     * @param $priority int
     */
    private function createSitemapEntry($url, $modifiedDate, $changeFrequency, $priority)
    {
        $this->event->getGenerator()->addUrl(
            new UrlConcrete(
                $url,
                $modifiedDate,
                $changeFrequency,
                $priority
            ),
            'default'
        );
    }
}
