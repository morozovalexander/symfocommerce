<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\FixturesProviderTrait;
use AppBundle\Utils\Slugger;
use AppBundle\Entity\StaticPage;

class StaticPagesFixtures extends AbstractFixture
{
    use FixturesProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getAllStaticPageTitles() as $i => $title) {
            $staticPage = new StaticPage();
            $staticPage->setTitle($title);
            $staticPage->setOrderNum($i);
            $staticPage->setEnabled(true);
            $staticPage->setMetaKeys($this->getRandomMetaKeysString());
            $staticPage->setMetaDescription($this->getRandomMetaDescriptionString());
            $staticPage->setSlug(Slugger::slugify($staticPage->getTitle()));
            $staticPage->setContent($this->getLongTextContent());
            $manager->persist($staticPage);
        }

        $manager->flush();
    }
}
