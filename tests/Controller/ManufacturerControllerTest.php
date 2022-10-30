<?php

namespace App\Tests\Controller;

use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Eshop\FixturesBundle\DataFixtures\ORM\ManufacturerFixtures;
use Eshop\FixturesBundle\Utils\Slugger;
use Eshop\ShopBundle\Entity\Manufacturer;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerControllerTest extends WebTestCase
{
    use FixturesProviderTrait;

    private $client;

    public function setUp()
    {
        $this->loadFixtures([
            LoadUserData::class,
            ManufacturerFixtures::class
        ]);

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function testAdminSeesManufacturersList()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/admin/manufacturer');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#admin-manufacturers-index')->count(),
            'Manufacturers list title shown.'
        );

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('table.records_list a.manufacturer-name')->count(),
            'Manufacturers list shown.'
        );
    }

    public function testAdminCreateNewManufacturer()
    {
        $manufacturerName = 'Test Manufacturer Name' . mt_rand();
        $manufacturerSlug = Slugger::slugify($manufacturerName);
        $manufacturerDescription = $this->getLongTextContent();
        $manufacturerMetaKeys = $this->getRandomMetaKeysString();
        $manufacturerMetaDescription = $this->getRandomMetaDescriptionString();

        $crawler = $this->client->request('GET', '/admin/manufacturer/new');

        $form = $crawler->selectButton('Create')->form([
            'eshop_shopbundle_manufacturer[name]' => $manufacturerName,
            'eshop_shopbundle_manufacturer[description]' => $manufacturerDescription,
            'eshop_shopbundle_manufacturer[metaKeys]' => $manufacturerMetaKeys,
            'eshop_shopbundle_manufacturer[metaDescription]' => $manufacturerMetaDescription,
            'eshop_shopbundle_manufacturer[slug]' => $manufacturerSlug
        ]);

        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Manufacturer manufacturer */
        $manufacturer = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Manufacturer::class)
            ->findOneBy(['slug' => $manufacturerSlug]);

        $this->assertNotNull($manufacturer);

        $this->assertSame($manufacturerName, $manufacturer->getName());
        $this->assertSame($manufacturerDescription, $manufacturer->getDescription());
        $this->assertSame($manufacturerMetaKeys, $manufacturer->getMetaKeys());
        $this->assertSame($manufacturerMetaDescription, $manufacturer->getMetaDescription());
    }

    public function testAdminShowManufacturer()
    {
        $this->client->request('GET', '/admin/manufacturer/1');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditManufacturer()
    {
        $manufacturerName = 'Test Manufacturer Name' . mt_rand();

        $crawler = $this->client->request('GET', '/admin/manufacturer/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'eshop_shopbundle_manufacturer[name]' => $manufacturerName,
        ]);
        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Manufacturer $manufacturer */
        $manufacturer = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Manufacturer::class)
            ->find(1);

        $this->assertNotNull($manufacturer);
        $this->assertSame($manufacturerName, $manufacturer->getName());
    }

    public function testAdminDeleteManufacturer()
    {
        $crawler = $this->client->request('GET', '/admin/manufacturer/1');

        $this->client->submit($crawler->selectButton('Delete')->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $manufacturer = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(Manufacturer::class)
            ->find(1);
        $this->assertNull($manufacturer);
    }
}
