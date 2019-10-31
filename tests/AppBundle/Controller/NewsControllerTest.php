<?php

namespace Tests\AppBundle\Controller;

use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\DataFixtures\ORM\NewsFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Eshop\FixturesBundle\Utils\Slugger;
use Eshop\ShopBundle\Entity\News;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NewsControllerTest extends WebTestCase
{
    use FixturesProviderTrait;

    private $client;

    public function setUp()
    {
        $this->loadFixtures([
            LoadUserData::class,
            NewsFixtures::class
        ]);

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function testAdminSeesNewsList()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/admin/news');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#admin-news-index')->count(),
            'News list title shown.'
        );

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('table.records_list a.news-title')->count(),
            'News list shown.'
        );
    }

    public function testAdminCreateNews()
    {
        $newsTitle = 'Test News Title' . mt_rand();
        $newsSlug = Slugger::slugify($newsTitle);
        $newsText = $this->getLongTextContent();
        $newsMetaKeys = $this->getRandomMetaKeysString();
        $newsMetaDescription = $this->getRandomMetaDescriptionString();

        $crawler = $this->client->request('GET', '/admin/news/new');

        $form = $crawler->selectButton('Create')->form([
            'eshop_shopbundle_news[title]' => $newsTitle,
            'eshop_shopbundle_news[text]' => $newsText,
            'eshop_shopbundle_news[metaKeys]' => $newsMetaKeys,
            'eshop_shopbundle_news[metaDescription]' => $newsMetaDescription,
            'eshop_shopbundle_news[slug]' => $newsSlug
        ]);

        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var News $news */
        $news = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(News::class)
            ->findOneBy(['slug' => $newsSlug]);

        $this->assertNotNull($news);

        $this->assertSame($newsTitle, $news->getTitle());
        $this->assertSame($newsText, $news->getText());
        $this->assertSame($newsMetaKeys, $news->getMetaKeys());
        $this->assertSame($newsMetaDescription, $news->getMetaDescription());
    }

    public function testAdminShowNews()
    {
        $this->client->request('GET', '/admin/news/1');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditNews()
    {
        $newsTitle = 'Test News Name' . mt_rand();

        $crawler = $this->client->request('GET', '/admin/news/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'eshop_shopbundle_news[title]' => $newsTitle,
        ]);
        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var News $news */
        $news = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(News::class)
            ->find(1);

        $this->assertNotNull($news);
        $this->assertSame($newsTitle, $news->getTitle());
    }

    public function testAdminDeleteNews()
    {
        $crawler = $this->client->request('GET', '/admin/news/1');

        $this->client->submit($crawler->selectButton('Delete')->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $news = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(News::class)
            ->find(1);
        $this->assertNull($news);
    }
}
