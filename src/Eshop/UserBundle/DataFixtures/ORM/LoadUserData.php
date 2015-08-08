<?php
namespace Eshop\UserBundle\DataFixtures\ORM;

use Eshop\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        $userAdmin->setEmail('admin@email.com');

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userAdmin)
        ;
        $userAdmin->setPassword($encoder->encodePassword('admin', $userAdmin->getSalt()));

        $manager->persist($userAdmin);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}