<?php

namespace App\Service;

use App\Repository\SettingsRepository;
use App\Entity\Settings;

class SettingsService
{
    /** @var Settings $settings */
    private $settings;

    /**
     * SettingsService constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository) {
        $this->settings = $settingsRepository->findAll()[0];
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
