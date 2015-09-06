<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CatalogController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="index_main")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('ShopBundle:Category');
        $manufacturerRepository = $em->getRepository('ShopBundle:Manufacturer');

        $categories = $categoryRepository->findAll();
        $manufacturers = $manufacturerRepository->findAll();

        return array(
            'categories' => $categories,
            'manufacturers' => $manufacturers
        );
    }
}
