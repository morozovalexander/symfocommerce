<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\ShopBundle\Entity\Settings;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Settings controller.
 *
 * @Route("/admin/settings")
 */
class SettingsController extends Controller
{

    /**
     * Show current settings.
     *
     * @Route("/", methods={"GET"}, name="admin_settings")
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $settingRepository = $em->getRepository('ShopBundle:Settings');
        $entities = $settingRepository->findAll();

        return $this->render('admin/settings/index.html.twig', [
            'settings' => $entities[0]
        ]);
    }

    /**
     * @param Request $request
     * @Route("/settings_edit", methods={"POST"}, name="admin_settings_edit")
     * @return JsonResponse
     */
    public function settingsEditAction(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $settingRepository = $em->getRepository('ShopBundle:Settings');
        $entities = $settingRepository->findAll();
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
