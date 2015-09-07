<?php
namespace Eshop\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eshop\ShopBundle\Entity\Manufacturer;

class LoadManufacturerData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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

        $manufacturerssArray = array(
            '4DN',
            'ActivLab',
            'API',
            'ARTLab',
            'aTech Nutrition',
            'Axis Labs',
            'BodyStrong',
            'BSN',
            'CytoSport',
            'Dymatize',
            'FortiFX',
            'Gaspari Nutrition',
            'Hardlabz',
            'Inner Armour',
            'IRON MAN',
            'IronMan',
            'KING PROTEIN',
            'Labrada',
            'Magnum',
            'Maxler',
            'Mex Nutrition',
            'MHP',
            'Multipower',
            'Musclepharm',
            'Muscletech',
            'Mutant',
            'Nanox',
            'NOW',
            'Nutrex',
            'Olimp',
            'Optimum Nutrition',
            'OstroVit',
            'Performance',
            'ProMera Sports',
            'PureProtein',
            'PVL',
            'RUSSPORT',
            'SAN',
            'Spotrpit Nutrition',
            'STS-Sports',
            'Supreme Protein',
            'Syntrax',
            'TSP',
            'Twinlab',
            'Ultimate Nutrition',
            'Universal Nutrition',
            'USN',
            'USPLabs',
            'VP Laboratory',
            'Weider',
            'другие'
        );

        //create manufacturer
        foreach ($manufacturerssArray as $manufacturerName) {
            $category = new Manufacturer();
            $category->setName($manufacturerName);
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
        return 3; // the order in which fixtures will be loaded
    }
}