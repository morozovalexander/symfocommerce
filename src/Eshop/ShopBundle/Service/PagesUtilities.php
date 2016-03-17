<?php
namespace Eshop\ShopBundle\Service;

use Symfony\Component\HttpFoundation\Request;

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
}
