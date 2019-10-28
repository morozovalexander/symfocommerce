<?php

namespace Eshop\ShopBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eshop\ShopBundle\Entity\Settings;

/**
 * Class SettingsService
 * @package Eshop\ShopBundle\Service
 */
class SettingsService
{
    /** @var Settings $settings */
    private $settings;

    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * SettingsService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $allSettings = $this->em->getRepository('ShopBundle:Settings')->findAll();
        $this->settings = $allSettings[0];
    }

    /**
     * @return bool
     */
    public function getShowEmptyManufacturers(): bool
    {
        return $this->settings->getShowEmptyManufacturers();
    }

    /**
     * @return bool
     */
    public function getShowEmptyCategories(): bool
    {
        return $this->settings->getShowEmptyCategories();
    }
}
