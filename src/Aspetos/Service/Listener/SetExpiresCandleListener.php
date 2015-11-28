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

use Aspetos\Service\Event\CandleEvent;
use Aspetos\Service\Event\StatefulEvent;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class SetExpiresCandleListener
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.subscriber.expires_candle")
 */
class SetExpiresCandleListener
{
    /**
     * @var ReminderService
     */
    protected $reminderService;

    /**
     * @param CandleEvent $event
     *
     * @DI\Observe("aspetos.event.candle.create.pre", priority = 255)
     */
    public function setExpire(CandleEvent $event)
    {
        $candle = $event->getCandle();
        if ($candle->getProduct() === null) {
            return;
        }

        $days = $candle->getProduct()->getLifeTime();
        $interval = new \DateInterval(sprintf('P%sD', $days));

        $created = $candle->getCreatedAt();
        $expiers = $created->add($interval);
        $candle->setExpiresAt($expiers);
    }

}
