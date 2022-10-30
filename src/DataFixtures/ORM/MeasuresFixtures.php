<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\FixturesProviderTrait;
use App\Entity\Measure;

class MeasuresFixtures extends AbstractFixture
{
    use FixturesProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getMeasureTitles() as $title) {
            $measure = new Measure();
            $measure->setName($title);
            $manager->persist($measure);
        }

        $manager->flush();
    }
}
