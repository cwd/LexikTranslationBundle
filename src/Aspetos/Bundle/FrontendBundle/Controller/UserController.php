<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Aspetos\Service\Exception\ReminderNotFoundException;
use Cwd\GenericBundle\LegacyHelper\BaseIntEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @Route("/reminder/activate/{token}")
     * @return RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $reminderId = BaseIntEncoder::decode($request->get('token'))/1234567890;
        try {
            $reminder = $this->get('aspetos.service.reminder')->find($reminderId);
            $reminder->activate();
            $this->get('aspetos.service.reminder')->flush();
            // @TODO ACTIVATE ALL by this email address!
            // @TODO Error Handling - Flashmessenger
        } catch (ReminderNotFoundException $e) {
        }

        return $this->redirectToRoute('aspetos_frontend_default_index');
    }
}
