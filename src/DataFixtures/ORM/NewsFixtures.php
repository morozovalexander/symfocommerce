<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\FixturesProviderTrait;
use App\Utils\Slugger;
use App\Entity\News;

class NewsFixtures extends AbstractFixture
{
    use FixturesProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getRandomNewsTitles() as $title) {
            $news = new News();
            $news->setTitle($title);
            $news->setSlug(Slugger::slugify($news->getTitle()));
            $news->setText($this->getLongTextContent());
            $news->setMetaKeys($this->getRandomMetaKeysString());
            $news->setMetaDescription($this->getRandomMetaDescriptionString());
            $manager->persist($news);
        }

        $manager->flush();
    }
}
