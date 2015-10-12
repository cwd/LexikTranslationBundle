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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * AuthController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @Route("/auth")
 */
class AuthenticationController extends Controller
{
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
        return $this->redirect($this->generateUrl('aspetos_admin_user_edit', array('id' => $this->getUser()->getId())));
    }

    /**
     * Logout BC redirect
     *
     * @Route("/_logout", name="auth_logout")
     * @Method({"GET"})
     * @Secure(roles="ROLE_USER")
     *
     * @return Response
     */
    public function logoutAction()
    {
        return $this->redirect($this->generateUrl('fos_user_security_logout'));
    }
}
