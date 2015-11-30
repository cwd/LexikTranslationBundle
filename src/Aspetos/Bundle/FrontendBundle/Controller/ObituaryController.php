<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Aspetos\Model\Entity\District;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Region;
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
    protected $sortFields = array('obituary.createdAt', 'obituary.dayOfDeath');
    const TYPE_DEFAULT = 0;
    const TYPE_PROMINENT = 1;
    const TYPE_CHILDREN = 2;
    const TYPE_ANNIVERSARIES = 3;
    const TYPE_MORTICIAN = 4;

    /**
     * @param String  $slug
     * @param Request $request
     *
     * @Route("/bestatter/{slug}")
     * @ParamConverter("mortician", class="Model:Mortician", options={"mapping": {"slug" = "slug"}})
     * @return array()
     */
    public function morticianAction(Mortician $mortician, Request $request)
    {
        return $this->listAction(null, null, $request, self::TYPE_MORTICIAN, $mortician);
    }

    /**
     * @param String  $region
     * @param String  $district
     * @param Request $request
     *
     * @Route("/prominente/{region}/{district}", defaults={"region" = null, "district" = null})
     * @return array()
     */
    public function prominentAction($region, $district, Request $request)
    {
        return $this->listAction($region, $district, $request, self::TYPE_PROMINENT);
    }

    /**
     * @param String  $region
     * @param String  $district
     * @param Request $request
     *
     * @Route("/kinder/{region}/{district}", defaults={"region" = null, "district" = null})
     * @return array()
     */
    public function childrenAction($region, $district, Request $request)
    {
        return $this->listAction($region, $district, $request, self::TYPE_CHILDREN);
    }

    /**
     * @param String  $region
     * @param String  $district
     * @param Request $request
     *
     * @Route("/jahrestage/{region}/{district}", defaults={"region" = null, "district" = null})
     * @return array()
     */
    public function anniversariesAction($region, $district, Request $request)
    {
        return $this->listAction($region, $district, $request, self::TYPE_ANNIVERSARIES);
    }

    /**
     * @param string  $region
     * @param string  $district
     * @param Request $request
     * @param int     $type
     * @param Mortician $mortician
     *
     * @Route("/{region}/{district}", defaults={"region" = null, "district" = null})
     * @return array()
     */
    public function listAction($region, $district, Request $request, $type = self::TYPE_DEFAULT, Mortician $mortician = null)
    {
        $search = array();
        $districts = array();

        if ($region !== null) {
            $districtService = $this->get('aspetos.service.district');

            if ($district !== null) {
                $slug = $region . '/' . $district;
                $districts = array($districtService->findBySlug($slug)->getId());
            } else {
                $regionService = $this->get('aspetos.service.region');
                /** @var Region $region */
                $region = $regionService->findBySlug($region);
                $districts = $region->getDistricts()->map(function($entity) {
                    return $entity->getId();
                })->toArray();
            }
            $search['obituary.district'] = $districts;
        }

        switch ($type) {
            case self::TYPE_DEFAULT:
                $search['obituary.type'] = array(Obituary::TYPE_NORMAL, Obituary::TYPE_CHILD);
                break;
            case self::TYPE_PROMINENT:
                $search['obituary.type'] = Obituary::TYPE_PROMINENT;
                break;
            case self::TYPE_CHILDREN:
                $search['obituary.type'] = Obituary::TYPE_CHILD;
                break;
            case self::TYPE_ANNIVERSARIES:
                $search["DATE_FORMAT(obituary.dayOfDeath, '%d.%c')"] = date('d.m');
                break;
            case self::TYPE_MORTICIAN:
                $search['mortician'] = $mortician->getId();
                break;
        }

        $service = $this->get('aspetos.service.obituary');

        $getDistricts = true;
        $template = 'list.html.twig';
        if ($request->isMethod('POST')) {
            $getDistricts = false;
            $template = 'items.html.twig';
        }

        $data = $this->getData($service, $request, $search, $getDistricts);
        $data['selectedDistricts'] = $districts;

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
