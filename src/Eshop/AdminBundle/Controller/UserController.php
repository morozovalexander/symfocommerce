<?php

namespace Eshop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ads controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('UserBundle:User');
        $paginator = $this->get('knp_paginator');

        $qb = $userRepository->getAllUsersAdminQB();
        $limit = $this->getParameter('admin_users_pagination_count');

        $entities = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return array(
            'entities' => $entities
        );
    }
}
