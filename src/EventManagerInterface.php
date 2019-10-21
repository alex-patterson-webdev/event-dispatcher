<?php

namespace Arp\EventManager;

use Arp\EventManager\Exception\EventDispatcherException;
use Arp\EventManager\Exception\InvalidArgumentException;

/**
 * EventManagerInterface
 *
 * @package Arp\EventDispatcher
 */
interface EventManagerInterface extends EventDispatcherInterface
{
    /**
     * attachListener
     *
     * Attach a new event listener with the provided priority.
     *
     * @param string   $name      The name of the event to attach to.
     * @param callable $listener  The event listener that will be attached.
     * @param integer  $priority  The default event priority.
     */
    public function attachListener($name, callable $listener, $priority = 1);

    /**
     * attachSubscriber
     *
     * Attach a collection of event listeners via a subscriber.
     *
     * @param EventSubscriberInterface $subscriber
     */
    public function attachSubscriber(EventSubscriberInterface $subscriber);

    /**
     * trigger
     *
     * Create and trigger an event by it's name.
     *
     * @param string  $name     The name of the event to trigger.
     * @param array   $params   The optional event parameters.
     * @param mixed   $context  The context of the event.
     *
     * @throws EventDispatcherException
     */
    public function trigger($name, array $params = [], $context = null);

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
    public function createEvent($name, array $data = [], $context = null);

    /**
     * setEventClassName
     *
     * @param string $eventClassName
     *
     * @throws InvalidArgumentException
     */
    public function setEventClassName($eventClassName);

}