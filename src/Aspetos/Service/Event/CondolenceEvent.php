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

use Aspetos\Model\Entity\Condolence;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CondolenceEvent
 *
 * @package Neos\Service\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CondolenceEvent extends Event
{
    /**
     * @var Condolence
     */
    protected $condolence;

    /**
     * @param Condolence $condolence
     */
    public function __construct(Condolence $condolence)
    {
        $this->condolence = $condolence;
    }

    /**
     * @return Condolence
     */
    public function getCondolence()
    {
        return $this->condolence;
    }
}
