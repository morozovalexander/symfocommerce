<?php

namespace Eshop\ShopBundle\Controller;

use Eshop\ShopBundle\Entity\Favourites;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/ajax_like", name="ajax_like")
     * @Method("POST")
     */
    public function likeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('ShopBundle:Product');
        $favouritesRepository = $em->getRepository('ShopBundle:Favourites');

        $productId = $request->request->getInt('product_id');
        $product = $productRepository->find($productId);
        $user = $this->getUser();

        if (!is_object($product)) {
            return $this->returnErrorJson('product not found');
        } elseif (!is_object($user)) {
            return $this->returnErrorJson('user not found');
        }

        $favoriteRecord = $favouritesRepository->findOneBy(array(
            'user' => $this->getUser(),
            'product' => $product
        ));

        $liked = false;
        if (!is_object($favoriteRecord)) {
            $favoriteRecord = new Favourites; //add like
            $favoriteRecord->setUser($this->getUser());
            $favoriteRecord->setProduct($product);
            $favoriteRecord->setDate(new \DateTime());
            $em->persist($favoriteRecord);
            $liked = true;
        } else {
            $em->remove($favoriteRecord); //remove like
        }

        $em->flush();

        return new JsonResponse(array(
            'favourite' => $liked,
            'success' => true
        ), 200);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function returnErrorJson($message)
    {
        return new JsonResponse(
            array(
                'success' => false,
                'message' => $message
            ),
            400
        );
    }
}
