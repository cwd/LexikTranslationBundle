<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Event;

use Aspetos\Model\Entity\BaseUser;
use Aspetos\Service\UserInterface as AspetosUserInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEvent
 *
 * @package Neos\Service\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class UserEvent extends Event
{
    /**
     * @var BaseUser
     */
    protected $user;

    /**
     * @param BaseUser $user
     */
    public function __construct(AspetosUserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
