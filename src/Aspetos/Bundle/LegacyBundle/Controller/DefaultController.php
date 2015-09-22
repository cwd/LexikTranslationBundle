<?php

namespace Aspetos\Bundle\LegacyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @package Aspetos\Bundle\LegacyBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class DefaultController extends Controller
{
    /**
     * @param string $name
     *
     * @Route("/hello/{name}")
     * @Template()
     * @return array
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
}
