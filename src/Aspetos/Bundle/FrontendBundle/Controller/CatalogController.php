<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            'cemeteries'    => $cemeteryService->findByCountry($country, 0, 20),
            'districts'     => $districtService->findByCountry($country)
        );
    }
}
