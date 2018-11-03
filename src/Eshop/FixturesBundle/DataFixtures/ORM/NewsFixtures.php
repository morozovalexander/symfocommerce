<?php

namespace Eshop\FixturesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Eshop\FixturesBundle\DataFixtures\FixturesProviderTrait;
use Eshop\FixturesBundle\Utils\Slugger;
use Eshop\ShopBundle\Entity\News;

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
