<?php

namespace Eshop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CatalogController extends Controller
{
    /**
     * @Route("/")
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
