<?php
namespace Aspetos\Service\Listener;

use Doctrine\Common\Util\Inflector;
use Finite\Event\FiniteEvents;
use Finite\Event\TransitionEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use KPhoen\DoctrineStateMachineBehavior\Listener\StatefulEntitiesCallbacksListener as KphoenCallbackListener;

/**
 * Call class specific events
 * The available callbacks are the following (all are optionnal):
 *  * onPre{Transition}: called before the transition {Transition} is applied ;
 *  * onPost{Transition}: called after the transition {Transition} is applied ;
 *  * onCan{Transition}: called when the state machine checks if {Transition}
 *    can be applied.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 * @author Ludwig Ruderstaller <lr@cwd.at>
 */
class StatefulEntitiesCallbacksListener extends KphoenCallbackListener
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @DI\InjectParams({
     *     "dispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->getSubscribedEvents(); // only for coverage :)
    }

    /**
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FiniteEvents::PRE_TRANSITION    => 'onPreTransition',
            FiniteEvents::POST_TRANSITION   => 'onPostTransition',
            FiniteEvents::TEST_TRANSITION   => 'onTestTransition',
        ];
    }

    /**
     * Triggers AFTER transistion happens
     * @param TransitionEvent $event
     */
    public function onPostTransition(TransitionEvent $event)
    {
        $object = $event->getStateMachine()->getObject();
        $this->triggerEvent($object, 'post', $event->getTransition()->getName());
    }

    /**
     * Triggers BEVOR transistion happens
     * @param TransitionEvent $event
     */
    public function onPreTransition(TransitionEvent $event)
    {
        $object = $event->getStateMachine()->getObject();
        $this->triggerEvent($object, 'pre', $event->getTransition()->getName());
    }

    /**
     * @param misc   $object
     * @param string $type
     * @param string $eventName
     */
    protected function triggerEvent($object, $type, $eventName)
    {
        $class = get_class($object);

        $classPart = null;
        $eventClass = null;

        if (0 === strpos($class, 'Aspetos\Model\Entity')) {
            // Fallback Event Class
            $classPart = Container::underscore('Stateful');

            $className = substr($class, 21);
            $eventClass = 'Aspetos\\Service\\Event\\'.$className.'Event';
            if (class_exists($eventClass)) {
                $classPart = Container::underscore($className);
            }
        }

        if (null !== $classPart && null !== $eventClass) {
            $event = sprintf('aspetos.event.state.%s.%s.%s', $classPart, $eventName, $type);
            $this->dispatcher->dispatch($event, new $eventClass($object));
        }
    }
}
