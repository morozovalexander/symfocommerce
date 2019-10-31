<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\FixturesProviderTrait;
use AppBundle\Utils\Slugger;
use AppBundle\Entity\Manufacturer;

class ManufacturerFixtures extends AbstractFixture
{
    use FixturesProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getRandomManufacturerTitles() as $title) {
            $manufacturer = new Manufacturer();
            $manufacturer->setName($title);
            $manufacturer->setSlug(Slugger::slugify($manufacturer->getName()));
            $manufacturer->setDescription($this->getLongTextContent());
            $manufacturer->setMetaKeys($this->getRandomMetaKeysString());
            $manufacturer->setMetaDescription($this->getRandomMetaDescriptionString());
            $manager->persist($manufacturer);
        }

        $manager->flush();
    }
}
