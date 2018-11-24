<?php

namespace AdminBundle\Controller;

use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\DataFixtures\ORM\MeasuresFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Eshop\ShopBundle\Entity\Measure;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MeasureControllerTest extends WebTestCase
{
    use FixturesProviderTrait;

    private $client;

    public function setUp()
    {
        $this->loadFixtures([
            LoadUserData::class,
            MeasuresFixtures::class
        ]);

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function testAdminSeesMeasuresList()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/admin/measure');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#admin-measures-index')->count(),
            'Measures list title shown.'
        );

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('table.records_list a.measure-name')->count(),
            'Measures list shown.'
        );
    }

    public function testAdminCreateNewMeasure()
    {
        $measureName = 'Test Measure Name' . mt_rand();

        $crawler = $this->client->request('GET', '/admin/measure/new');

        $form = $crawler->selectButton('Create')->form([
            'eshop_shopbundle_measure[name]' => $measureName
        ]);

        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Measure $measure */
        $measure = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Measure::class)
            ->findOneBy(['name' => $measureName]);

        $this->assertNotNull($measure);
    }

    public function testAdminShowMeasure()
    {
        $this->client->request('GET', '/admin/measure/1');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEditMeasure()
    {
        $measureName = 'Test Measure Name' . mt_rand();

        $crawler = $this->client->request('GET', '/admin/measure/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'eshop_shopbundle_measure[name]' => $measureName,
        ]);
        $this->client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        /** @var Measure $measure */
        $measure = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Measure::class)
            ->find(1);

        $this->assertNotNull($measure);
        $this->assertSame($measureName, $measure->getName());
    }

    public function testAdminDeleteMeasure()
    {
        $crawler = $this->client->request('GET', '/admin/measure/1');

        $this->client->submit($crawler->selectButton('Delete')->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $measure = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(Measure::class)
            ->find(1);
        $this->assertNull($measure);
    }
}
