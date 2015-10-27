<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @Route("/")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     * @return array()
     */
    public function indexAction()
    {
        return array('name' => 'fff');
    }

    /**
     * @Route("/_closemodal");
     * @Template()
     * @return array()
     */
    public function closeModalAction()
    {
        return array();
    }
}
