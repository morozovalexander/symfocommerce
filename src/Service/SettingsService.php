<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Settings;

/**
 * Class SettingsService
 * @package App\Service
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
        $allSettings = $this->em->getRepository(Settings::class)->findAll();
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