<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class AjaxAuthenticationListener
 *
 * @package Aspetos\Service\Listener
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class AjaxAuthenticationListener
{
    /**
     * Handles security related exceptions.
     *
     * @param GetResponseForExceptionEvent $event An GetResponseForExceptionEvent instance
     */
    public function onCoreException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();
        if ($request->isXmlHttpRequest()) {
            if ($exception instanceof AuthenticationException || $exception instanceof AccessDeniedException) {
                $event->setResponse(new Response('', 403));
            }
        }
    }
}
