<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Settings;

class LoadSettingsData implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $settings = new Settings();

        $settings->setShowEmptyCategories(1);
        $settings->setShowEmptyManufacturers(1);

        $manager->persist($settings);
        $manager->flush();
    }
}
