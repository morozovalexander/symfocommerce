<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\FixturesProviderTrait;
use App\Utils\Slugger;
use App\Entity\Category;

class CategoryFixtures extends AbstractFixture
{
    use FixturesProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getRandomCategoryTitles() as $title) {
            $category = new Category();
            $category->setName($title);
            $category->setSlug(Slugger::slugify($category->getName()));
            $category->setDescription($this->getLongTextContent());
            $category->setMetaKeys($this->getRandomMetaKeysString());
            $category->setMetaDescription($this->getRandomMetaDescriptionString());
            $manager->persist($category);
        }

        $manager->flush();
    }
}
