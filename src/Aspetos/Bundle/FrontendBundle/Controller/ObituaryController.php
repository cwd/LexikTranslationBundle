<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Aspetos\Model\Entity\Obituary;
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
 * @Route("/obituary")
 */
class ObituaryController extends Controller
{
    /**
     * @param Obituary $obituary
     * @param Request  $request
     *
     * @Route("/{id}")
     * @ParamConverter("obituary", class="Model:Obituary")
     * @Template()
     * @return array()
     */
    public function detailAction(Obituary $obituary, Request $request)
    {
        return array(
            'obituary' => $obituary
        );
    }
}
