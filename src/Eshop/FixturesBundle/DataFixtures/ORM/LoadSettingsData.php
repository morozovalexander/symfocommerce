<?php

namespace Eshop\AdminBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Eshop\ShopBundle\Entity\Settings;

class LoadSettingsData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $settings = new Settings();

        $settings->setShowEmptyCategories(1);
        $settings->setShowEmptyManufacturers(1);

        $manager->persist($settings);
        $manager->flush();
    }
}
