<?php
/*
 * This file is part of Aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Service\Event;

use Finite\StatefulInterface;
use Symfony\Component\EventDispatcher\Event;

/**
* Class StatefulEvent
*
* @package Aspetos\Service\Event
* @author  Ludwig Ruderstaller <lr@cwd.at>
*/
class StatefulEvent extends Event
{
    /**
    * @var StatefulInterface
    */
    protected $entity;

    /**
    * @param StatefulInterface $entity
    */
    public function __construct(StatefulInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
    * @return StatefulInterface
    */
    public function getObject()
    {
        return $this->entity;
    }
}
