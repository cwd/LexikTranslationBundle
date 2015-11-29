<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Aspetos\Model\Entity\District;
use Aspetos\Model\Entity\Mortician;
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
 * Class MorticianController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/bestatter")
 */
class MorticianController extends BaseController
{

    /**
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @Route("/{slug}",  requirements={"slug"=".+"})
     * @ParamConverter("mortician", class="Model:Mortician", options={"mapping": {"slug" = "slug"}})
     * @Template()
     * @return array()
     */
    public function detailAction(Mortician $mortician, Request $request)
    {
        $eventService = $this->get('aspetos.service.obituary.event');
        $eventSearch = array(
            'obituary.mortician' => $mortician
        );

        return array(
            'mortician' => $mortician,
            'events'    => $eventService->search($eventSearch, null, true, 0, 5)
        );
    }
}
