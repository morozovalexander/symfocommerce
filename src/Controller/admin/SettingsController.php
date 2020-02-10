<?php

namespace App\Controller\admin;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Settings controller.
 *
 * @Route("/admin/settings")
 */
class SettingsController extends AbstractController
{
    /**
     * Show current settings.
     *
     * @Route("/", methods={"GET"}, name="admin_settings")
     * @param SettingsRepository $settingsRepository
     * @return Response
     */
    public function indexAction(SettingsRepository $settingsRepository): Response
    {
        return $this->render('admin/settings/index.html.twig', [
            'settings' => $settingsRepository->findAll()[0]
        ]);
    }

    /**
     * @param Request $request
     * @param SettingsRepository $settingsRepository
     * @return JsonResponse
     * @Route("/settings_edit", methods={"POST"}, name="admin_settings_edit")
     */
    public function settingsEditAction(Request $request, SettingsRepository $settingsRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $settingsRepository->findAll();
        /**
         * @var Settings $settings
         */
        $settings = $entities[0];


        $editingSetting = $request->get('editing_setting');
        $newValue = $request->request->getBoolean('new_value');

        switch ($editingSetting) {
            case 'show_empty_categories';
                $settings->setShowEmptyCategories($newValue);
                break;
            case 'show_empty_manufacturers';
                $settings->setShowEmptyManufacturers($newValue);
                break;
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
