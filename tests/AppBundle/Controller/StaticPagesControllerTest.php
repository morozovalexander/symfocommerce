<?php

namespace Tests\AppBundle\Controller;

use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\DataFixtures\ORM\StaticPagesFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Eshop\FixturesBundle\Utils\Slugger;
use Eshop\ShopBundle\Entity\StaticPage;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StaticPagesControllerTest extends WebTestCase
{
    use FixturesProviderTrait;

    private $client;

    public function setUp()
    {
        $this->loadFixtures([
            LoadUserData::class,
            StaticPagesFixtures::class
        ]);

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function testAdminSeesStaticPagesList()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/admin/staticpage');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#admin-staticpage-index')->count(),
            'Static Pages list title shown.'
        );

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('table.records_list a.staticpage-title')->count(),
            'StaticPages list shown.'
        );
    }

    public function testAdminCreateStaticPage()
    {
        $staticPageTitle = 'Test Static Page Title' . mt_rand();
        $staticPageSlug = Slugger::slugify($staticPageTitle);
        $staticPageContent = $this->getLongTextContent();
        $staticPageMetaKeys = $this->getRandomMetaKeysString();
        $staticPageMetaDescription = $this->getRandomMetaDescriptionString();

        $crawler = $this->client->request('GET', '/admin/staticpage/new');

        $form = $crawler->selectButton('Create')->form([
            'eshop_shopbundle_staticpage[title]' => $staticPageTitle,
            'eshop_shopbundle_staticpage[content]' => $staticPageContent,
            'eshop_shopbundle_staticpage[metaKeys]' => $staticPageMetaKeys,
            'eshop_shopbundle_staticpage[metaDescription]' => $staticPageMetaDescription,
            'eshop_shopbundle_staticpage[slug]' => $staticPageSlug,
            'eshop_shopbundle_staticpage[orderNum]' => 999
        ]);

        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var StaticPage $staticPage */
        $staticPage = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(StaticPage::class)
            ->findOneBy(['slug' => $staticPageSlug]);

        $this->assertNotNull($staticPage);

        $this->assertSame($staticPageTitle, $staticPage->getTitle());
        $this->assertSame($staticPageContent, $staticPage->getContent());
        $this->assertSame($staticPageMetaKeys, $staticPage->getMetaKeys());
        $this->assertSame($staticPageMetaDescription, $staticPage->getMetaDescription());
    }

    public function testAdminShowStaticPage()
    {
        $this->client->request('GET', '/admin/staticpage/1');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditStaticPage()
    {
        $staticPageTitle = 'Test Static Page Title' . mt_rand();

        $crawler = $this->client->request('GET', '/admin/staticpage/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'eshop_shopbundle_staticpage[title]' => $staticPageTitle,
        ]);
        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var StaticPage $staticPage */
        $staticPage = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(StaticPage::class)
            ->find(1);

        $this->assertNotNull($staticPage);
        $this->assertSame($staticPageTitle, $staticPage->getTitle());
    }

    public function testAdminDeleteStaticPage()
    {
        $crawler = $this->client->request('GET', '/admin/staticpage/1');

        $this->client->submit($crawler->selectButton('Delete')->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $staticPage = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(StaticPage::class)
            ->find(1);
        $this->assertNull($staticPage);
    }
}
