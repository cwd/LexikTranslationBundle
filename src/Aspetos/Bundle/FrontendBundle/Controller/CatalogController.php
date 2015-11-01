<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class CatalogController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/")
 */
class CatalogController extends Controller
{
    /**
     * @Route("catalog")
     * @Template()
     * @return array()
     */
    public function indexAction()
    {
        return array();
    }
}
