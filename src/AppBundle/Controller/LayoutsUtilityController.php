<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Manufacturer;
use AppBundle\Entity\StaticPage;
use AppBundle\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LayoutsUtilityController extends Controller
{
    /**
     * render categories menu
     */
    public function categoriesMenuAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);

        $settings = $this->get(SettingsService::class);
        $showEmpty = $settings->getShowEmptyCategories();

        $categories = $categoryRepository->getAllCategories($showEmpty);

        return $this->render('_partials/categories_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * render manufacturers menu
     */
    public function manufacturersMenuAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturerRepository = $em->getRepository(Manufacturer::class);

        $settings = $this->get(SettingsService::class);
        $showEmpty = $settings->getShowEmptyManufacturers();

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
