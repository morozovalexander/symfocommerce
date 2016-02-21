<?php
namespace Eshop\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eshop\ShopBundle\Entity\Slide;

class LoadSlidesData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $titles = array(
            'slide1',
            'slide2'
        );

        $i = 1;
        foreach ($titles as $title) {
            $slide = new Slide();
            $slide->setName($title);
            $slide->setPath($title . '.jpg');
            $slide->setEnabled(true);
            $slide->setSlideOrder($i);
            $i++;

            $manager->persist($slide);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 8; // the order in which fixtures will be loaded
    }
}