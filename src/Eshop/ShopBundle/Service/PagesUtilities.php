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
