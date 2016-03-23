<?php
namespace Eshop\ShopBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PagesUtilities
{
    /**
     * return sorting name param from request
     *
     * @param Request $request
     * @return string
     */
    public function getSortingParamName(Request $request)
    {
        $sortedBy = '';
        $sortParam = $request->get('sort');

        switch ($sortParam) {
            case 'p.name':
                $sortedBy = 'manufacturer.sort.name';
                break;
            case 'p.price':
                $sortedBy = 'manufacturer.sort.price';
                break;
            default:
                $sortedBy = 'manufacturer.sort.default';
                break;
        }
        return $sortedBy;
    }

    /**
     * return last seen products from cookies
     *
     * @param Request $request
     * @return array
     */
    public function getLastSeenProducts(Request $request)
    {
        $cookies = $request->cookies->all();

        if (isset($cookies['last-seen'])) {
            $productIdsArray = json_decode($cookies['last-seen']);

            if (is_array($productIdsArray) && !empty($productIdsArray)) {
                return $productIdsArray;
            }
        }
        return false;
    }

    /**
     * clear cookies cart
     *
     * @return void
     */
    public function clearCart()
    {
        $response = new Response();
        $response->headers->clearCookie('cart');
        $response->sendHeaders();
    }
}
