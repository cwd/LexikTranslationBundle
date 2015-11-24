<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Cwd\GenericBundle\Service\Generic;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/")
 */
class DefaultController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/")
     * @Template()
     * @return array()
     */
    public function indexAction(Request $request)
    {
        $service = $this->get('aspetos.service.obituary');

        return $this->getData($service, $request, array(), true);
    }

    /**
     * @Route("/filter")
     * @Template()
     * @param Request $request
     * @return array()
     */
    public function itemsAction(Request $request)
    {
        $service = $this->get('aspetos.service.obituary');

        return $this->getData($service, $request);
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
        $search['obituary.country'] = $request->attributes->get('country');
        $districts = $request->get('districts');
        if ($districts != null) {
            $search['obituary.district'] = $districts;
        }

        return parent::getData($service, $request, $search, $getDistricts);
    }
}
