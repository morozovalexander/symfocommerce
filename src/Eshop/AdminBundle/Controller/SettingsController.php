<?php

namespace Eshop\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eshop\ShopBundle\Entity\Settings;

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
     * @Route("/", name="admin_settings")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $settingRepository = $em->getRepository('ShopBundle:Settings');

        $entities = $settingRepository->findAll();

        return array(
            'entities' => $entities,
        );
    }
}
