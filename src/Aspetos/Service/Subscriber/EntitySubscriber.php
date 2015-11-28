<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EntitySubscriber
 *
 * @package Aspetos\Subscriber
 * @author  David Herrmann <office@web-emerge.com>
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.subscriber.entity")
 * @DI\DoctrineListener(
 *     events = {"prePersist", "preUpdate", "preRemove", "postPersist", "postUpdate", "postRemove"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0,
 * )
 */
class EntitySubscriber implements EventSubscriber
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
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->triggerEvent($args, 'create.pre');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->triggerEvent($args, 'create.post');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->triggerEvent($args, 'edit.pre');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->triggerEvent($args, 'edit.post');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->triggerEvent($args, 'remove.pre');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $this->triggerEvent($args, 'remove.post');
    }

    /**
     * @param LifecycleEventArgs $args
     * @param                    $eventName
     */
    protected function triggerEvent(LifecycleEventArgs $args, $eventName)
    {
        $class = get_class($args->getObject());

        $classPart = null;
        $eventClass = null;
        switch ($class) {
            // Demo for further documentation:
            /*
            case 'Some\Class\Bar':
                $classPart = 'bar';
                $eventClass = 'Some\Event\BarEvent';
                break;
            */
            case 'Aspetos\Model\Entity\Admin':
            case 'Aspetos\Model\Entity\MorticianUser':
            case 'Aspetos\Model\Entity\SupplierUser':
            case 'Aspetos\Model\Entity\Customer':
                $classPart = 'user';
                $eventClass = 'Aspetos\\Service\\Event\\UserEvent';
                break;

            default:
                if (0 === strpos($class, 'Aspetos\Model\Entity')) {
                    $className = substr($class, 21);
                    $eventClass = 'Aspetos\\Service\\Event\\'.$className.'Event';
                    if (class_exists($eventClass)) {
                        $classPart = Container::underscore($className);
                    }
                }
        }



        if (null !== $classPart && null !== $eventClass) {
            $event = sprintf('aspetos.event.%s.%s', $classPart, $eventName);
            dump($event);
            $this->dispatcher->dispatch($event, new $eventClass($args->getObject()));
        }
    }
}
