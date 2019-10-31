<?php

namespace AppBundle\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Category controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="admin_index")
     */
    public function indexAction(): Response
    {
        return $this->render('admin/admin/index.html.twig');
    }
}
