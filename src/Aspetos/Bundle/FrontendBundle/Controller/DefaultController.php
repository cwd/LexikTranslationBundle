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
        return $this->redirectToRoute('aspetos_frontend_obituary_list');
    }
}
