<?php

namespace Aspetos\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;

/**
 * Class SecurityController
 *
 * @package Aspetos\Bundle\UserBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class SecurityController extends BaseSecurityController
{
    /**
     * {@inheritDoc}
     */
    public function renderLogin(array $data)
    {
        $requestAttributes = $this->container->get('request')->attributes;

        if ('admin_login' === $requestAttributes->get('_route')) {
            $template = sprintf('AspetosAdminBundle:Security:login.html.twig');
        } else {
            $template = sprintf('AspetosFrontendBundle:Security:login.html.twig');
        }

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
