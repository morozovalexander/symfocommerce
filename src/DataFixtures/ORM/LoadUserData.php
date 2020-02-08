<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        //create admin
        $userAdmin = new User();
        $userAdmin->setFirstname('admin');
        $userAdmin->setLastname('admin');
        $userAdmin->setUsername('admin');
        $userAdmin->setPhone('1234567890');
        $userAdmin->setAddress('admin address');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setEmail('admin@example.com');

        $encodedPassword = $passwordEncoder->encodePassword($userAdmin, 'admin');
        $userAdmin->setPassword($encodedPassword);

        $manager->persist($userAdmin);

        //create test users
        for ($i = 1; $i < 50; $i++) {
            $user = new User();
            $user->setFirstname('Firstname' . $i);
            $user->setLastname('Lastname' . $i);
            $user->setPhone('1234567890 ' . $i);
            $user->setAddress('address ' . $i);
            $user->setUsername('user' . $i);
            $user->setEnabled(true);
            $user->setEmail('user' . $i . '@email.com');

            $encodedPassword = $passwordEncoder->encodePassword($user, 'user' . $i);
            $user->setPassword($encodedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}