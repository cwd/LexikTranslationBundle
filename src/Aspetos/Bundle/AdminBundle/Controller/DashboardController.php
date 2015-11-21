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

use Aspetos\Service\ReminderService;
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

    /**
     * @Route("/test")
     * @Template("AspetosAdminBundle:Dashboard:index.html.twig")
     * @return array
     */
    public function tmpTestAction()
    {
        $obituary = $this->get('aspetos.service.obituary')->find(48821);
        /** @var ReminderService $reminderService */
        $reminderService = $this->get('aspetos.service.reminder');
        $remindDate = new \DateTime('2015-11-18');
        $remindDate->add(new \DateInterval('P1Y'));
        dump($remindDate->format('Y-m-d'));
        $reminderService->addReminder($obituary, 'office@cwd.at', $remindDate);

        return array('fooo'=>'bar');
    }
}
