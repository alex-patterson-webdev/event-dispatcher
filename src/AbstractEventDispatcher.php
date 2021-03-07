<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher
 */
abstract class AbstractEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface
     */
    protected ListenerProviderInterface $listenerProvider;

    /**
     * Trigger the registered collection of events.
     *
     * @param object $event The event that should be triggered.
     *
     * @return object
     *
     * @throws \Throwable If an event listener throws an exception
     */
    public function dispatch(object $event): object
    {
        if ($this->isPropagationStopped($event)) {
            return $event;
        }

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            $listener($event);

            if ($this->isPropagationStopped($event)) {
                break;
            }
        }

        return $event;
    }

    /**
     * Check if the event propagation has been stopped.
     *
     * @param object $event
     *
     * @return bool
     */
    protected function isPropagationStopped(object $event): bool
    {
        return ($event instanceof StoppableEventInterface && $event->isPropagationStopped());
    }
}
