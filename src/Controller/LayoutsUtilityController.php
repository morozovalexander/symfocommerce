<?php

namespace App\Controller;

use App\Repository\StaticPageRepository;
use App\Service\Category;
use App\Service\Manufacturer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LayoutsUtilityController extends AbstractController
{
    /**
     * Render categories menu
     * @param Category $category
     * @return Response
     */
    public function categoriesMenu(Category $category): Response
    {
        return $this->render('_partials/categories_menu.html.twig', [
            'categories' => $category->getCategoriesForMenu()
        ]);
    }

    /**
     * Render manufacturers menu
     * @param Manufacturer $manufacturer
     * @return Response
     */
    public function manufacturersMenu(Manufacturer $manufacturer): Response
    {
        return $this->render('_partials/manufacturers_menu.html.twig', [
            'manufacturers' => $manufacturer->getManufacturersForMenu()
        ]);
    }

    /**
     * Render top menu with static pages headers.
     * @param StaticPageRepository $staticPageRepository
     * @return Response
     */
    public function staticPagesMenu(StaticPageRepository $staticPageRepository): Response
    {
        $headers = $staticPageRepository->getHeaders();
        return $this->render('_partials/static_pages_menu.html.twig', [
            'headers' => $headers
        ]);
    }
}
