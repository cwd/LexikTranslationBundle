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

use Aspetos\Model\Entity\Obituary;
use Symfony\Component\EventDispatcher\Event;

/**
* Class ObituaryEvent
*
* @package Aspetos\Service\Event
* @author  Ludwig Ruderstaller <lr@cwd.at>
*/
class ObituaryEvent extends Event
{
    /**
    * @var Obituary
    */
    protected $entity;

    /**
    * @param Obituary $entity
    */
    public function __construct(Obituary $entity)
    {
        $this->entity = $entity;
    }

    /**
    * @return Obituary
    */
    public function getObituary()
    {
        return $this->entity;
    }
}