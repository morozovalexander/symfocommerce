<?php

namespace App\Tests\Controller;

use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\DataFixtures\ORM\CategoryFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Eshop\FixturesBundle\DataFixtures\ORM\ManufacturerFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\MeasuresFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\ProductFixtures;
use Eshop\FixturesBundle\Utils\Slugger;
use Eshop\ShopBundle\Entity\Product;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    use FixturesProviderTrait;

    private $client;

    public function setUp()
    {
        $this->loadFixtures([
            LoadUserData::class,
            CategoryFixtures::class,
            ManufacturerFixtures::class,
            MeasuresFixtures::class,
            ProductFixtures::class,
        ]);

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function testAdminSeesProductsList()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/admin/product');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#admin-products-index')->count(),
            'Products list title shown.'
        );

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('table.records_list a.product-name')->count(),
            'Products list shown.'
        );
    }

    public function testAdminCreateNewProduct()
    {
        $productName = 'Test Product Name' . mt_rand();
        $productSlug = Slugger::slugify($productName);
        $productPrice = random_int(1, 1000);
        $productDescription = $this->getLongTextContent();
        $productMetaKeys = $this->getRandomMetaKeysString();
        $productMetaDescription = $this->getRandomMetaDescriptionString();

        $crawler = $this->client->request('GET', '/admin/product/new');

        $form = $crawler->selectButton('Create')->form([
            'eshop_shopbundle_product[name]' => $productName,
            'eshop_shopbundle_product[price]' => $productPrice,
            'eshop_shopbundle_product[description]' => $productDescription,
            'eshop_shopbundle_product[metaKeys]' => $productMetaKeys,
            'eshop_shopbundle_product[metaDescription]' => $productMetaDescription,
            'eshop_shopbundle_product[slug]' => $productSlug,
            'eshop_shopbundle_product[category]' => 1,
            'eshop_shopbundle_product[manufacturer]' => 1,
            'eshop_shopbundle_product[measure]' => 1,
            'eshop_shopbundle_product[quantity]' => 1,
            'eshop_shopbundle_product[measureQuantity]' => 1
        ]);

        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Product $product */
        $product = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Product::class)
            ->findOneBy(['slug' => $productSlug]);

        $this->assertNotNull($product);

        $this->assertSame($productName, $product->getName());
        $this->assertSame($productPrice, (int)$product->getPrice());
        $this->assertSame($productDescription, $product->getDescription());
        $this->assertSame($productMetaKeys, $product->getMetaKeys());
        $this->assertSame($productMetaDescription, $product->getMetaDescription());
        $this->assertSame(1, $product->getQuantity());
        $this->assertSame(1, $product->getCategory()->getId());
        $this->assertSame(1, $product->getManufacturer()->getId());
        $this->assertSame(1, $product->getMeasure()->getId());
        $this->assertSame(1, $product->getMeasureQuantity());
    }

    public function testAdminShowProduct()
    {
        $this->client->request('GET', '/admin/product/1');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditProduct()
    {
        $productName = 'Test Product Name' . mt_rand();
        $productPrice = random_int(1, 1000);
        $productDescription = $this->getLongTextContent();
        $productMetaKeys = $this->getRandomMetaKeysString();
        $productMetaDescription = $this->getRandomMetaDescriptionString();

        $crawler = $this->client->request('GET', '/admin/product/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'eshop_shopbundle_product[name]' => $productName,
            'eshop_shopbundle_product[price]' => $productPrice,
            'eshop_shopbundle_product[description]' => $productDescription,
            'eshop_shopbundle_product[metaKeys]' => $productMetaKeys,
            'eshop_shopbundle_product[metaDescription]' => $productMetaDescription,
            'eshop_shopbundle_product[category]' => 2,
            'eshop_shopbundle_product[manufacturer]' => 2,
            'eshop_shopbundle_product[measure]' => 2,
            'eshop_shopbundle_product[quantity]' => 2,
            'eshop_shopbundle_product[measureQuantity]' => 2
        ]);
        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Product $product */
        $product = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Product::class)
            ->find(1);

        $this->assertNotNull($product);
        $this->assertSame($productName, $product->getName());
        $this->assertSame($productPrice, (int)$product->getPrice());
        $this->assertSame($productDescription, $product->getDescription());
        $this->assertSame($productMetaKeys, $product->getMetaKeys());
        $this->assertSame($productMetaDescription, $product->getMetaDescription());
        $this->assertSame(2, $product->getQuantity());
        $this->assertSame(2, $product->getCategory()->getId());
        $this->assertSame(2, $product->getManufacturer()->getId());
        $this->assertSame(2, $product->getMeasure()->getId());
        $this->assertSame(2, $product->getMeasureQuantity());
    }

    public function testAdminDeleteProduct()
    {
        $crawler = $this->client->request('GET', '/admin/product/1');

        $this->client->submit($crawler->selectButton('Delete')->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $product = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(Product::class)
            ->find(1);
        $this->assertNull($product);
    }
}
