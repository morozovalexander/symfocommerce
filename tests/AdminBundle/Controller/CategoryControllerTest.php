<?php

namespace AdminBundle\Controller;

use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\DataFixtures\ORM\CategoryFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Eshop\FixturesBundle\Utils\Slugger;
use Eshop\ShopBundle\Entity\Category;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
    use FixturesProviderTrait;

    private $client;

    public function setUp()
    {
        $this->loadFixtures([
            LoadUserData::class,
            CategoryFixtures::class
        ]);

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function testAdminSeesCategoriesList()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/admin/category');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#admin-categories-index')->count(),
            'Categories list title shown.'
        );

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('table.records_list a.category-name')->count(),
            'Categories list shown.'
        );
    }

    public function testAdminCreateNewCategory()
    {
        $categoryName = 'Test Category Name' . mt_rand();
        $categorySlug = Slugger::slugify($categoryName);
        $categoryDescription = $this->getLongTextContent();
        $categoryMetaKeys = $this->getRandomMetaKeysString();
        $categoryMetaDescription = $this->getRandomMetaDescriptionString();

        $crawler = $this->client->request('GET', '/admin/category/new');

        $form = $crawler->selectButton('Create')->form([
            'eshop_shopbundle_category[name]' => $categoryName,
            'eshop_shopbundle_category[description]' => $categoryDescription,
            'eshop_shopbundle_category[metaKeys]' => $categoryMetaKeys,
            'eshop_shopbundle_category[metaDescription]' => $categoryMetaDescription,
            'eshop_shopbundle_category[slug]' => $categorySlug
        ]);

        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Category $category */
        $category = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Category::class)
            ->findOneBy(['slug' => $categorySlug]);

        $this->assertNotNull($category);

        $this->assertSame($categoryName, $category->getName());
        $this->assertSame($categoryDescription, $category->getDescription());
        $this->assertSame($categoryMetaKeys, $category->getMetaKeys());
        $this->assertSame($categoryMetaDescription, $category->getMetaDescription());
    }

    public function testAdminShowCategory()
    {
        $this->client->request('GET', '/admin/category/1');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditCategory()
    {
        $categoryName = 'Test Category Name' . mt_rand();

        $crawler = $this->client->request('GET', '/admin/category/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'eshop_shopbundle_category[name]' => $categoryName,
        ]);
        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Category $category */
        $category = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Category::class)
            ->find(1);

        $this->assertNotNull($category);
        $this->assertSame($categoryName, $category->getName());
    }

    public function testAdminDeleteCategory()
    {
        $crawler = $this->client->request('GET', '/admin/category/1');

        $this->client->submit($crawler->selectButton('Delete')->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $category = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(Category::class)
            ->find(1);
        $this->assertNull($category);
    }
}
