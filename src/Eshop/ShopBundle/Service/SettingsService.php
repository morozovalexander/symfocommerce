<?php
namespace Eshop\ShopBundle\Service;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Settings;

class SettingsService
{
    /**
     * @var Settings $settings
     */
    private $settings;

    /**
     * @var EntityManager $em
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $allSettings = $this->em->getRepository('ShopBundle:Settings')->findAll();
        $this->settings = $allSettings[0];
    }

    /**
     * @return bool
     */
    public function getShowEmptyManufacturers(){
        return $this->settings->getShowEmptyManufacturers();
    }

    /**
     * @return bool
     */
    public function getShowEmptyCategories(){
        return $this->settings->getShowEmptyCategories();
    }
}
