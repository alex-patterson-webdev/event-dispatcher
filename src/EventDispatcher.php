<?php

namespace Arp\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

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
        if ($this->isPropagationStopped($event)) {
            return $event;
        }

        foreach($this->listenerProvider->getListenersForEvent($event) as $listener) {

            $listener($event);

            if ($this->isPropagationStopped($event)) {
                break;
            }
        }

        return $event;
    }

    /**
     * @param object $event
     *
     * @return bool
     */
    private function isPropagationStopped(object $event) : bool
    {
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            return true;
        }

        return false;
    }
}
