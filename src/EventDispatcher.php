<?php

namespace Arp\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * EventDispatcher
 *
 * @package Arp\EventDispatcher
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface
     */
    protected $listenerProvider;

    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider  = $listenerProvider;
    }

    /**
     * Trigger the registered collection of events.
     *
     * @param object $event  The event that should be triggered.
     *
     * @return object
     */
    public function dispatch(object $event)
    {
        foreach($this->listenerProvider->getListenersForEvent($event) as $listener) {

            $result = $listener($event);

            if (false === $result || ($event instanceof StoppableEventInterface && $event->isPropagationStopped())) {
                break;
            }
        }

        return $event;
    }
}
