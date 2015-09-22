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

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Security;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cwd\GenericBundle\Controller\AuthController as CwdAuthController;


/**
 * AuthController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @Route("/auth")
 */
class AuthenticationController extends CwdAuthController
{
    /**
     * Login Action
     *
     * @param Request $request
     *
     * @Route("/login", name="auth_login")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @return array
     */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(Security::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $request->getSession()->get(Security::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * Lets user update his profile
     *
     * @Route("/profile")
     * @Method({"GET"})
     * @Secure(roles="ROLE_USER")
     *
     * @return Response
     */
    public function profileAction()
    {
        return parent::redirectProfileAction('aspetos_admin_user_edit');
    }

    /**
     * @param Request $request
     *
     * @Route("/lostpassword")
     * @Template()
     * @Method({"GET", "POST"})
     * @return array
     */
    public function lostpasswordAction(Request $request)
    {
        return parent::handleLostpasswordAction($request, 'AspetosAdminBundle:Email:lostpassword.html.twig');
    }
}
