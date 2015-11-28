<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Aspetos\Model\Entity\Obituary;
use Cwd\GenericBundle\Service\Generic;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ObituaryController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/parten")
 */
class ObituaryController extends BaseController
{

    /**
     * @param String  $district
     * @param String  $region
     * @param Request $request
     *
     * @Route("/{district}/{region}", defaults={"district" = null, "region" = null})
     * @return array()
     */
    public function listAction($district, $region, Request $request)
    {
        $service = $this->get('aspetos.service.obituary');

        $search = array();
        $getDistricts = true;
        $template = 'list.html.twig';
        if ($request->isXmlHttpRequest()) {
            $getDistricts = false;
            $template = 'items.html.twig';
        }

        $data = $this->getData($service, $request, $search, $getDistricts);

        return $this->render('AspetosFrontendBundle:Obituary:' . $template, $data);
    }

    /**
     * @param Obituary $obituary
     * @param Request  $request
     *
     * @Route("/{slug}",  requirements={"slug"=".+"})
     * @ParamConverter("obituary", class="Model:Obituary", options={"mapping": {"slug" = "slug"}})
     * @Template()
     * @return array()
     */
    public function detailAction(Obituary $obituary, Request $request)
    {
        $candleService = $this->get('aspetos.service.obituary.candle');
        $candleSearch = array(
            'candle.obituary' => $obituary
        );

        $eventService = $this->get('aspetos.service.obituary.event');
        $eventSearch = array(
            'event.obituary' => $obituary
        );

        return array(
            'obituary'    => $obituary,
            'candles'     => $candleService->search($candleSearch),
            'events'      => $eventService->search($eventSearch)
        );
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
