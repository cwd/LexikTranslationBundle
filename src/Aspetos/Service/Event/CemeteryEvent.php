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

use Aspetos\Model\Entity\Cemetery;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CemeteryEvent
 *
 * @package Neos\Service\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CemeteryEvent extends Event
{
    /**
     * @var Cemetery
     */
    protected $cemetery;

    /**
     * @param Cemetery $cemetery
     */
    public function __construct(Cemetery $cemetery)
    {
        $this->cemetery = $cemetery;
    }

    /**
     * @return Cemetery
     */
    public function getCemetery()
    {
        return $this->cemetery;
    }
}
