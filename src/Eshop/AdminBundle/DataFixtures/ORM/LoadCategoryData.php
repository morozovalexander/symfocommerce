<?php
namespace Eshop\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eshop\ShopBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $categoriesArray = array(
            'Главная',
            'Аминокислоты BCAA',
            'Анаболические комплексы',
            'Витаминно-минеральные комплексы',
            'Гейнеры',
            'Для Суставов и Связок',
            'Жиросжигатели',
            'Изотоники',
            'Креатин',
            'Одежда, перчатки, сумки',
            'Пампинг',
            'Пептиды',
            'Повышение Тестостерона',
            'Послетренировочные комплексы',
            'Предтренировочные комплексы',
            'Протеин',
            'Протеиновые батончики',
            'Шейкеры и акссесуары',
            'Энергетики',
        );

        //create categories
        foreach ($categoriesArray as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. One more time!
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua.');
            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}