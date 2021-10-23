<?php

namespace App\Service;

use App\Entity\Manufacturer as ManufacturerEntity;
use App\Repository\ManufacturerRepository;

class Manufacturer
{
    /** @var ManufacturerRepository */
    private $manufacturerRepository;
    /** @var SettingsService */
    private $settingsService;

    /**
     * @param ManufacturerRepository $manufacturerRepository
     * @param SettingsService $settingsService
     */
    public function __construct(
        ManufacturerRepository $manufacturerRepository,
        SettingsService $settingsService
    ) {
        $this->manufacturerRepository = $manufacturerRepository;
        $this->settingsService = $settingsService;
    }

    /**
     * @return ManufacturerEntity[]
     */
    public function getManufacturersForMenu(): array
    {
        return $this->manufacturerRepository->getAllManufacturers(
            $this->settingsService->getShowEmptyManufacturers()
        );
    }
}