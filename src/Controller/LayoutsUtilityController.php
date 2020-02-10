<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\StaticPage;
use App\Repository\CategoryRepository;
use App\Repository\ManufacturerRepository;
use App\Repository\StaticPageRepository;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LayoutsUtilityController extends AbstractController
{
    /**
     * render categories menu
     * @param SettingsService $settingsService
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function categoriesMenuAction(
        SettingsService $settingsService,
        CategoryRepository $categoryRepository
    ): Response {
        $showEmpty = $settingsService->getShowEmptyCategories();

        $categories = $categoryRepository->getAllCategories($showEmpty);

        return $this->render('_partials/categories_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * render manufacturers menu
     * @param SettingsService $settingsService
     * @param ManufacturerRepository $manufacturerRepository
     * @return Response
     */
    public function manufacturersMenuAction(
        SettingsService $settingsService,
        ManufacturerRepository $manufacturerRepository
    ): Response {
        $showEmpty = $settingsService->getShowEmptyManufacturers();
        $manufacturers = $manufacturerRepository->getAllManufacturers($showEmpty);

        return $this->render('_partials/manufacturers_menu.html.twig', [
            'manufacturers' => $manufacturers
        ]);
    }

    /**
     * render top menu with static pages headers.
     * @param StaticPageRepository $staticPageRepository
     * @return Response
     */
    public function staticPagesMenuAction(StaticPageRepository $staticPageRepository): Response
    {
        $headers = $staticPageRepository->getHeaders();
        return $this->render('_partials/static_pages_menu.html.twig', [
            'headers' => $headers
        ]);
    }
}
