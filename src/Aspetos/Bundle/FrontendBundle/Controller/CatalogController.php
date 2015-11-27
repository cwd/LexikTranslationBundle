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
class CatalogController extends BaseController
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

        return $this->getData($service, $request, array(), true);
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

        return $this->getData($service, $request);
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

        return $this->getData($service, $request, array(), true);
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

        return $this->getData($service, $request);
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

        $data = $this->getData($service, $request, array(), true);
        $data['supplierTypes'] = $supplierTypeService->findAll();

        return $data;
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

        return $this->getData($service, $request, $search);
    }

    /**
     * @param Generic  $service
     * @param Request $request
     * @param array      $search
     * @param bool       $getDistricts
     * @return array
     */
    protected function getData(Generic $service, Request $request, $search = array(), $getDistricts = false)
    {
        $search['address.country'] = $request->attributes->get('country');

        $districts = $request->get('districts');
        if ($districts != null) {
            $search['address.district'] = $districts;
        }

        return parent::getData($service, $request, $search, $getDistricts);
    }
}
