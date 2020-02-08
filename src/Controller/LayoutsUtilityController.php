<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\StaticPage;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LayoutsUtilityController extends AbstractController
{
    /**
     * render categories menu
     * @param SettingsService $settingsService
     * @return Response
     */
    public function categoriesMenuAction(SettingsService $settingsService): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);

        $showEmpty = $settingsService->getShowEmptyCategories();

        $categories = $categoryRepository->getAllCategories($showEmpty);

        return $this->render('_partials/categories_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * render manufacturers menu
     * @param SettingsService $settingsService
     * @return Response
     */
    public function manufacturersMenuAction(SettingsService $settingsService): Response
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturerRepository = $em->getRepository(Manufacturer::class);

        $showEmpty = $settingsService->getShowEmptyManufacturers();

        $manufacturers = $manufacturerRepository->getAllManufacturers($showEmpty);

        return $this->render('_partials/manufacturers_menu.html.twig', [
            'manufacturers' => $manufacturers
        ]);
    }

    /**
     * render top menu with static pages headers.
     */
    public function staticPagesMenuAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $headers = $em->getRepository(StaticPage::class)->getHeaders();
        return $this->render('_partials/static_pages_menu.html.twig', [
            'headers' => $headers
        ]);
    }
}
