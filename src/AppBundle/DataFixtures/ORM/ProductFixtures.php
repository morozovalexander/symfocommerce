<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Manufacturer;
use AppBundle\Entity\Measure;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\FixturesProviderTrait;
use AppBundle\Utils\Slugger;
use AppBundle\Entity\Product;

class ProductFixtures extends AbstractFixture implements DependentFixtureInterface
{
    use FixturesProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $categoryRepository = $manager->getRepository(Category::class);
        $manufacturerRepository = $manager->getRepository(Manufacturer::class);
        $measureRepository = $manager->getRepository(Measure::class);
        //get all possible categories, manufacturers, measures
        $categories = $categoryRepository->findAll();
        $manufacturers = $manufacturerRepository->findAll();
        $measures = $measureRepository->findAll();
        for ($i = 1; $i <= 1000; $i++) {
            $product = new Product();
            $product->setName($this->getRandomProductName());
            $product->setSlug(Slugger::slugify($i . $product->getName()));
            $product->setDescription($this->getLongTextContent());
            //set random category or manufacturer
            $product->setCategory($categories[array_rand($categories)]);
            $product->setManufacturer($manufacturers[array_rand($manufacturers)]);
            $product->setMeasure($measures[array_rand($measures)]);
            $product->setMeasureQuantity(random_int(1, 10) * 100);
            $product->setQuantity(random_int(1, 10));
            $product->setPrice(random_int(1, 1000));
            $product->setMetaKeys($this->getRandomMetaKeysString());
            $product->setMetaDescription($this->getRandomMetaDescriptionString());
            $manager->persist($product);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            ManufacturerFixtures::class,
            MeasuresFixtures::class
        ];
    }
}
