<?php

namespace Arp\EventManager;

use Arp\EventManager\Exception\EventManagerException;
use Arp\EventManager\Exception\InvalidArgumentException;

/**
 * EventManager
 *
 * @package Arp\EventManager
 */
class EventManager implements EventManagerInterface
{
    /**
     * $listeners
     *
     * @var \SplPriorityQueue[]
     */
    protected $listeners;

    /**
     * $eventClassName
     *
     * @var string
     */
    protected $eventClassName = Event::class;

    /**
     * createEvent
     *
     * Create a new event instance.
     *
     * @param string  $name     The name of the event to create.
     * @param array   $data     The optional event data.
     * @param null    $context  The optional event context.
     *
     * @return EventInterface
     */
    public function createEvent($name, array $data = [], $context = null)
    {
        /** @var EventInterface $event */
        $event = new $this->eventClassName($name, $data);

        if (null !== $context) {
            $event->setContext($context);
        }

        return $event;
    }

    /**
     * attachListener
     *
     * Attach a new event listener with the provided priority.
     *
     * @param string   $name      The name of the event to attach to.
     * @param callable $listener  The event listener that will be attached.
     * @param integer  $priority  The default event priority.
     */
    public function attachListener($name, callable $listener, $priority = 1)
    {
        $this->getQueue($name)->insert($listener, $priority);
    }

    /**
     * attachSubscriber
     *
     * Attach a collection of event listeners via a subscriber.
     *
     * @param EventSubscriberInterface $subscriber
     */
    public function attachSubscriber(EventSubscriberInterface $subscriber)
    {
        $subscriber->subscribe($this);
    }

    /**
     * trigger
     *
     * Create and trigger an event by it's name.
     *
     * @param string  $name     The name of the event to trigger.
     * @param array   $params   The optional event parameters.
     * @param mixed   $context  The context of the event.
     *
     * @throws EventManagerException
     */
    public function trigger($name, array $params = [], $context = null)
    {
        $event = $this->createEvent($name, $params, $context);

        $this->triggerEvent($event);
    }

    /**
     * triggerEvent
     *
     * Trigger the registered collection of events.
     *
     * @param EventInterface $event
     *
     * @throws InvalidArgumentException
     */
    public function triggerEvent(EventInterface $event)
    {
        $name = $event->getName();

        if (empty($name)) {

            throw new InvalidArgumentException(sprintf(
                'The event must contain a \'name\' value in \'%s\'.',
                __METHOD__
            ));
        }

        foreach($this->getQueue($name) as $listener) {

            $result = $listener($event);

            if (false === $result || false === $event->propagate()) {
                // We have stopped propagation of the events.
                break;
            }
        }
    }

    /**
     * createQueue
     *
     * Return the event listener queue with the provided name.
     *
     * @param string $name
     *
     * @return \SplPriorityQueue
     */
    protected function getQueue($name)
    {
        if (empty($this->listeners[$name])) {
            $this->listeners[$name] = $this->createQueue();
        }

        return $this->listeners[$name];
    }

    /**
     * createQueue
     *
     * @return \SplPriorityQueue
     */
    protected function createQueue()
    {
        return new \SplPriorityQueue();
    }

    /**
     * setEventClassName
     *
     * @param string $eventClassName
     *
     * @throws InvalidArgumentException
     */
    public function setEventClassName($eventClassName)
    {
        if (! is_a($eventClassName, EventInterface::class, true)) {

            throw new InvalidArgumentException(sprintf(
                'The \'eventClassName\' argument must be an object of type \'%s\'; \'%s\' provided in \'%s\'.',
                EventInterface::class,
                (is_string($eventClassName) ? $eventClassName : gettype($eventClassName)),
                __METHOD__
            ));
        }

        $this->eventClassName = $eventClassName;
    }

}