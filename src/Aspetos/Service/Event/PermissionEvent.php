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

use Aspetos\Model\Entity\Permission;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PermissionEvent
 *
 * @package Neos\Service\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class PermissionEvent extends Event
{
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }
}
