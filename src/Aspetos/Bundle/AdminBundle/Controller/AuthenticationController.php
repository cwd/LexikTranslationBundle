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
class AuthenticationController
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
}
