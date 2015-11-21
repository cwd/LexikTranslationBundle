<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Listener\Transition;

use Aspetos\Service\Event\StatefulEvent;
use Aspetos\Service\ReminderService;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class ReminderSendOptInListener
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.subscriber.transition.reminder.sendoptin")
 */
class ReminderSendOptInListener
{
    /**
     * @var ReminderService
     */
    protected $reminderService;

    /**
     * ReminderSendOptInListener constructor.
     *
     * @param ReminderService $reminderService
     * @DI\InjectParams({
     *   "reminderService" = @DI\Inject("aspetos.service.reminder")
     * })
     */
    public function __construct(ReminderService $reminderService)
    {
        $this->reminderService = $reminderService;
    }

    /**
     * @param StatefulEvent $event
     *
     * @DI\Observe("aspetos.event.state.reminder.sendoptin.post", priority = 255)
     */
    public function sendOptin(StatefulEvent $event)
    {
        $this->reminderService->sendOptin($event->getObject());
    }

}
