<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CatalogController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/catalog")
 */
class CatalogController extends Controller
{
    const ITEMS_PER_PAGE = 5;

    /**
     * @Route("/cemeteries")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function cemeteriesAction(Request $request)
    {
        $country = $request->attributes->get('country');
        $cemeteryService = $this->get('aspetos.service.cemetery');
        $districtService = $this->get('aspetos.service.district');

        return array(
            'cemeteries'    => $cemeteryService->findByCountryAndDistricts($country, null, null, 0, self::ITEMS_PER_PAGE),
            'districts'     => $districtService->findByCountry($country)
        );
    }

    /**
     * @Route("/cemeteries/filter/{page}")
     * @Template()
     * @param int     $page
     * @param Request $request
     * @return array()
     */
    public function cemeteriesFilterAction($page, Request $request)
    {
        $country = $request->attributes->get('country');
        $cemeteryService = $this->get('aspetos.service.cemetery');

        $districts = null;
        $excludeIds = null;

        if (!empty($request->get('districts'))) {
            $districts = explode(',', $request->get('districts'));;
        }
        if (!empty($request->get('ids'))) {
            $excludeIds = explode(',', $request->get('ids'));
        }

        $page = intval($page);
        $nextPage = $page + 1;

        // ($page - 1) * self::ITEMS_PER_PAGE
        $cemeteries = $cemeteryService->findByCountryAndDistricts($country, $districts, $excludeIds, 0, self::ITEMS_PER_PAGE);
       if (sizeof($cemeteries) == 0) {
           $nextPage = false;
       }

        return array(
            'cemeteries'    => $cemeteries,
            'nextPage'      => $nextPage
        );
    }
}
