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

use Aspetos\Service\Event\UserEvent;
use Cwd\GenericBundle\LegacyHelper\Utils;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class UserPasswordListener
 *
 * @package Aspetos\Service\Listener
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.listener.userpassword", )
 */
class UserPasswordListener
{
    /**
     * @param UserEvent $event
     *
     * @DI\Observe("aspetos.event.user.create.pre", priority = 255)
     * @DI\Observe("aspetos.event.user.edit.pre", priority = 255)
     */
    public function setPassword(UserEvent $event)
    {
        $user = $event->getUser();
        if (!empty($user->getPlainPassword())) {
            $encoder = new Pbkdf2PasswordEncoder('sha512', true, 1000, 40);
            $salt = Utils::generateRandomString(20);
            $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $salt));
        }
    }
}
