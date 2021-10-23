<?php

namespace App\Service;

use App\Entity\Category as CategoryEntity;
use App\Repository\CategoryRepository;

class Category
{
    /** @var CategoryRepository */
    private $categoryRepository;
    /** @var SettingsService */
    private $settingsService;

    /**
     * @param CategoryRepository $categoryRepository
     * @param SettingsService $settingsService
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        SettingsService $settingsService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->settingsService = $settingsService;
    }

    /**
     * @return CategoryEntity[]
     */
    public function getCategoriesForMenu(): array
    {
        return $this->categoryRepository->getAllCategories(
            $this->settingsService->getShowEmptyCategories()
        );
    }
}