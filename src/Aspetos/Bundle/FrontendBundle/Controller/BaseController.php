<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Cwd\GenericBundle\Service\Generic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class BaseController extends Controller
{
    const ITEMS_PER_PAGE = 20;
    protected $sortDirections = array('DESC', 'ASC');
    protected $sortFields = array();

    /**
     * @param Generic  $service
     * @param Request $request
     * @param array      $search
     * @return array
     */
    protected function getData(Generic $service, Request $request, $search = array(), $getDistricts = false)
    {
        $exclude = $request->get('exclude');
        $sort = $this->parseSort($request);

        $data = array(
            'items' => $service->search($search, $exclude, 0, self::ITEMS_PER_PAGE, $sort)
        );

        if ($getDistricts) {
            $districtService = $this->get('aspetos.service.district');
            $country = $request->attributes->get('country');
            $data['districts'] = $districtService->findByCountry($country);
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function parseSort(Request $request)
    {
        $sort = array();
        $sortRaw = $request->get('sort');
        if ($sortRaw != null) {
            $sortParts = explode(':', $sortRaw);
            if (
                sizeof($sortParts) == 2
                && in_array($sortParts[0], $this->sortFields)
                && in_array($sortParts[1], $this->sortDirections)
            ) {
                $sort[$sortParts[0]] = $sortParts[1];
            }
        }

        return $sort;
    }
}
