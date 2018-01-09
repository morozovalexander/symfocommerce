<?php

namespace Eshop\FixturesBundle\DataFixtures\ORM;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


final class SymfonyPasswordProvider implements ContainerAwareInterface
{
//    /** @var EncoderFactoryInterface */
//    private $encoderFactory;

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

//    /**
//     * {@inheritdoc}
//     * @param EncoderFactoryInterface $encoderFactory
//     */
//    public function __construct(EncoderFactoryInterface $encoderFactory)
//    {
//        $this->encoderFactory = $encoderFactory;
//        $encoderFactory = $this->container->get('security.encoder_factory');
//    }

    /**
     * @param string $userClass
     * @param string $plainPassword
     * @param string|null $salt
     *
     * @return string
     */
    public function symfonyPassword(string $userClass, string $plainPassword, string $salt = null): string
    {
        dump($this->container);
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userClass);
        $password = $encoder->encodePassword($plainPassword, $salt);

        return $password;
//        return $this->generator->parse($password);
    }
}