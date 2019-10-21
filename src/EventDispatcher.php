<?php

namespace Arp\EventManager;

use Arp\EventManager\Exception\InvalidArgumentException;

/**
 * EventDispatcher
 *
 * @package Arp\EventDispatcher
 */
class EventDispatcher
{
    /**
     * $provider
     *
     * @var ListenerProviderInterface
     */
    protected $provider;

    /**
     * __construct
     *
     * @param ListenerCollectionInterface $listeners
     */
    public function __construct(ListenerProviderInterface $provider)
    {
        $this->listeners = $listeners;
    }

    /**
     * triggerEvent
     *
     * Trigger the registered collection of events.
     *
     * @param object $event
     *
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function dispatch($event)
    {
        $name = $event->getName();

        if (empty($name)) {

            throw new InvalidArgumentException(sprintf(
                'Unable to trigger event for instance that has no name in %s',
                __METHOD__
            ));
        }

        foreach($this->listeners->getListenersForEvent($event) as $listener) {

            $result = $listener($event);

            if (false === $result || ($event instanceof StoppableEventInterface && $event->isPropagationStopped())) {
                break;
            }
        }
    }


}