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

        return $this->catalog($service, $request);
    }

    /**
     * @Route("/cemeteries/filter")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function cemeteryItemsAction(Request $request)
    {
        $service = $this->get('aspetos.service.cemetery');

        return $this->filter($service, $request);
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

        return $this->catalog($service, $request);
    }

    /**
     * @Route("/morticians/filter")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function morticianItemsAction(Request $request)
    {
        $service = $this->get('aspetos.service.mortician');

        return $this->filter($service, $request);
    }

    /**
     * @Route("/suppliers")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function suppliersAction(Request $request)
    {
        $service = $this->get('aspetos.service.supplier.supplier');
        $supplierTypeService = $this->get('aspetos.service.supplier.type');

        $data = array(
            'supplierTypes' => $supplierTypeService->findAll()
        );

        return $this->catalog($service, $request, $data);
    }

    /**
     * @Route("/suppliers/filter")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function supplierItemsAction(Request $request)
    {
        $service = $this->get('aspetos.service.supplier.supplier');

        $search = array();
        $supplierTypes = $request->get('supplierTypes');
        if ($supplierTypes != null) {
            $search['supplierTypes.id'] = $supplierTypes;
        }

        return $this->filter($service, $request, $search);
    }

    /**
     * @param Generic $service
     * @param Request $request
     * @param array   $data
     * @return array
     */
    private function catalog(Generic $service, Request $request, $data = array())
    {
        $country = $request->attributes->get('country');
        $search = array('address.country' => $country);
        $districtService = $this->get('aspetos.service.district');

        $data['items'] = $service->search($search, null, 0, self::ITEMS_PER_PAGE);
        $data['districts'] = $districtService->findByCountry($country);

        return $data;
    }

    /**
     * @param Generic $service
     * @param Request $request
     * @param array   $search
     * @return array
     */
    private function filter(Generic $service, Request $request, $search = array())
    {
        $search['address.country'] = $request->attributes->get('country');

        $districts = $request->get('districts');
        if ($districts != null) {
            $search['address.district'] = $districts;
        }

        $exclude = $request->get('exclude');

        $items = $service->search($search, $exclude, 0, self::ITEMS_PER_PAGE);

        return array(
            'items'    => $items
        );
    }
}
