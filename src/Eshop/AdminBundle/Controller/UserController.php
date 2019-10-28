<?php

namespace Eshop\AdminBundle\Controller;

use Eshop\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/", methods={"GET"}, name="admin_user_list")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
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

        return $this->render('admin/user/index.html.twig', [
            'entities' => $entities
        ]);
    }

    /**
     * Shows user info
     *
     * @Route("/user/{id}", methods={"GET"}, name="admin_user_info")
     * @param User $user
     * @return Response
     */
    public function showUserInfoAction(User $user): Response
    {
        return $this->render('admin/user/show_user_info.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Shows users orders
     *
     * @Route("/user/{id}/orders", methods={"GET"}, name="admin_user_orders")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function showUserOrdersAction(Request $request, User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ordersRepository = $em->getRepository('ShopBundle:Orders');
        $paginator = $this->get('knp_paginator');

        $qb = $ordersRepository->getUserOrdersAdminQB($user);
        $limit = $this->getParameter('admin_user_orders_pagination_count');

        $orders = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $limit
        );

        return $this->render('admin/user/show_user_orders.html.twig', [
            'user' => $user,
            'orders' => $orders
        ]);
    }
}
