<?php
namespace Eshop\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eshop\ShopBundle\Entity\Category;
use Eshop\ShopBundle\Entity\Manufacturer;
use Eshop\ShopBundle\Entity\Product;

class LoadProductData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categoryRepository = $manager->getRepository('ShopBundle:Category');
        $manufacturerRepository = $manager->getRepository('ShopBundle:Manufacturer');
        $measureRepository = $manager->getRepository('ShopBundle:Measure');

        //get all possible categories, manufacturers, measures
        $categories = $categoryRepository->findAll();
        $manufacturers = $manufacturerRepository->findAll();
        $measures = $measureRepository->findAll();

        for ($i = 1; $i <= 1000; $i++) {
            $product = new Product();
            $product->setName('product ' . $i);
            $product->setSlug('product ' . $i);
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. One more time!
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua.');

            //set random category or manufacturer
            $product->setCategory($categories[array_rand($categories)]);
            $product->setManufacturer($manufacturers[array_rand($manufacturers)]);
            $product->setMeasure($measures[array_rand($measures)]);

            $product->setMeasureQuantity(mt_rand(1, 10) * 100);
            $product->setQuantity(mt_rand(1, 10));
            $product->setPrice(mt_rand(1, 1000));

            $manager->persist($product);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}