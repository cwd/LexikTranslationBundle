<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ObituaryController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/obituary")
 */
class ObituaryController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/{id}")
     * @Template()
     * @return array()
     */
    public function detailAction(Request $request)
    {
        $service = $this->get('aspetos.service.obituary');

        $country = $request->attributes->get('country');
        $search = array('obituary.country' => $country);
        $obituaries = $service->search($search);

        return array(
            'obituaries' => $obituaries
        );
    }
}
