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

use Aspetos\Model\Entity\User;
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
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
