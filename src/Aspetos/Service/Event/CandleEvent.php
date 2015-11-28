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

use Aspetos\Model\Entity\Candle;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CandleEvent
 *
 * @package Neos\Service\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CandleEvent extends Event
{
    /**
     * @var Candle
     */
    protected $candle;

    /**
     * @param Candle $candle
     */
    public function __construct(Candle $candle)
    {
        $this->candle = $candle;
    }

    /**
     * @return Candle
     */
    public function getCandle()
    {
        return $this->candle;
    }
}
