<?php
namespace Eshop\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eshop\ShopBundle\Entity\Measure;

class LoadMeasureData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $measureArray = array(
            'Grammes',
            'Pieces',
            'Caps',
            'Ml',
            'Packs'
        );

        //create measures
        foreach ($measureArray as $measureName) {
            $measure = new Measure();
            $measure->setName($measureName);
            $manager->persist($measure);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}