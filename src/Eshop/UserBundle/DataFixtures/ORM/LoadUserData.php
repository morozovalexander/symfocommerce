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
        //create admin
        $userAdmin = new User();
        $userAdmin->setFirstname('admin');
        $userAdmin->setLastname('admin');
        $userAdmin->setUsername('admin');
        $userAdmin->setPhone('1234567890');
        $userAdmin->setAddress('admin address');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setEmail('admin@email.com');

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userAdmin);

        $userAdmin->setPassword($encoder->encodePassword('admin', $userAdmin->getSalt()));

        $manager->persist($userAdmin);

        //create test users
        for ($i = 1; $i < 50; $i++) {
            $user = new User();
            $user->setFirstname('firstname' . $i);
            $user->setLastname('lastname' . $i);
            $user->setPhone('1234567890 ' . $i);
            $user->setAddress('address ' . $i);
            $user->setUsername('user' . $i);
            $user->setEnabled(true);
            $user->setEmail('user' . $i . '@email.com');

            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user);
            $user->setPassword($encoder->encodePassword('user' . $i, $user->getSalt()));
            $manager->persist($user);
        }

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