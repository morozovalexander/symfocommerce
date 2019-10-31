<?php

namespace Tests\AppBundle\Controller;

use Eshop\FixturesBundle\DataFixtures\ORM\CategoryFixtures;
use Eshop\FixturesBundle\DataFixtures\ORM\LoadUserData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsForRegularUsers
     */
    public function testAccessDeniedForRegularUsers($httpMethod, $url)
    {
        $this->loadFixtures([
            LoadUserData::class,
            CategoryFixtures::class
        ]);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'user1',
        ]);

        $client->followRedirects();
        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testAccessibleForAdmin()
    {
        $this->loadFixtures([
            LoadUserData::class,
            CategoryFixtures::class
        ]);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);

        $client->followRedirects();
        $crawler = $client->request('GET', '/admin/');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('h3#admin-index')->count(),
            'Backend is available.'
        );
    }

    public function getUrlsForRegularUsers()
    {
        yield ['GET', '/admin/'];
        yield ['GET', '/admin/category'];
        yield ['DELETE', '/admin/category/1'];
        yield ['GET', '/admin/category/1/edit'];
    }
}
