<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Cwd\GenericBundle\Service\Generic;
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
    const ITEMS_PER_PAGE = 20;

    /**
     * @Route("/cemeteries")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function cemeteriesAction(Request $request)
    {
        $service = $this->get('aspetos.service.cemetery');

        return $this->_list($service, $request);
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
        $service = $this->get('aspetos.service.cemetery');

        return $this->_filter($page, $service, $request);
    }

    /**
     * @Route("/morticians")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function morticiansAction(Request $request)
    {
        $service = $this->get('aspetos.service.mortician');

        return $this->_list($service, $request);
    }

    /**
     * @Route("/morticians/filter/{page}")
     * @Template()
     * @param int     $page
     * @param Request $request
     * @return array()
     */
    public function morticiansFilterAction($page, Request $request)
    {
        $service = $this->get('aspetos.service.mortician');

        return $this->_filter($page, $service, $request);
    }

    /**
     * @param Generic $service
     * @param Request $request
     * @return array
     */
    private function _list(Generic $service, Request $request)
    {
        $country = $request->attributes->get('country');
        $districtService = $this->get('aspetos.service.district');

        return array(
            'items'     => $service->findByCountryAndDistricts($country, null, null, 0, self::ITEMS_PER_PAGE),
            'districts' => $districtService->findByCountry($country)
        );
    }

    /**
     * @param $page
     * @param Generic $service
     * @param Request $request
     * @return array
     */
    private function _filter($page, Generic $service, Request $request)
    {
        $country = $request->attributes->get('country');

        $filter = null;
        $excludeIds = null;

        if (!empty($request->get('filter'))) {
            $filter = explode(',', $request->get('filter'));;
        }
        if (!empty($request->get('ids'))) {
            $excludeIds = explode(',', $request->get('ids'));
        }

        $page = intval($page);
        $nextPage = $page + 1;

        // ($page - 1) * self::ITEMS_PER_PAGE
        $items = $service->findByCountryAndDistricts($country, $filter, $excludeIds, 0, self::ITEMS_PER_PAGE);
        if (sizeof($items) == 0) {
            $nextPage = false;
        }

        return array(
            'items'    => $items,
            'nextPage' => $nextPage
        );
    }
}
