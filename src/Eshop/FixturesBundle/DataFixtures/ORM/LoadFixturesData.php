<?php

namespace Eshop\FixturesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;


class LoadFixturesData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $defLoader = new NativeLoader();
        $generator = $defLoader->createFakerGenerator();
        $generator->addProvider(new FakerProvider());

        $loader = new NativeLoader($generator);
        $objectSet = $loader->loadFile(__DIR__ . '/fixtures.yml',
            ['providers' => [$this]]
        )->getObjects();

        foreach($objectSet as $object) {
            $manager->persist($object);
        }
        $manager->flush();
    }
}
