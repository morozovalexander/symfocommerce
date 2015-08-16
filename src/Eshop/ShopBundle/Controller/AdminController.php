<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Category controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * Lists all Good entities.
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();

    }
}
