<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

abstract class AbstractEventDispatcher implements EventDispatcherInterface
{
    abstract protected function getListenerProvider(): ListenerProviderInterface;

    /**
     * @param object|StoppableEventInterface $event
     *
     * @throws \Throwable
     */
    public function dispatch(object $event): object
    {
        if ($this->isPropagationStopped($event)) {
            return $event;
        }

        foreach ($this->getListenerProvider()->getListenersForEvent($event) as $listener) {
            $listener($event);

            if ($this->isPropagationStopped($event)) {
                break;
            }
        }

        return $event;
    }

    /**
     * @param object|StoppableEventInterface $event
     */
    protected function isPropagationStopped(object $event): bool
    {
        return ($event instanceof StoppableEventInterface && $event->isPropagationStopped());
    }
}
