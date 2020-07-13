<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher
 */
final class EventDispatcher implements EventDispatcherInterface, AddListenerAwareInterface
{
    /**
     * @var ListenerProvider
     */
    private $listenerProvider;

    /**
     * @param ListenerProvider $listenerProvider
     */
    public function __construct(ListenerProvider $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * Trigger the registered collection of events.
     *
     * @param object $event The event that should be triggered.
     *
     * @return object
     *
     * @throws EventListenerException
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
     * Add a new event listener to the collection.
     *
     * @param object|string $event    The event that should be attached to.
     * @param callable      $listener The event listener to attach.
     * @param int           $priority The event priority.
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function addListenerForEvent($event, callable $listener, int $priority = 1): void
    {
        $this->listenerProvider->addListenerForEvent($event, $listener, $priority);
    }

    /**
     * Add a collection of event listeners for a single event.
     *
     * @param object|string       $event     The event name or instance to attach to.
     * @param iterable|callable[] $listeners Collection of listeners to attach.
     * @param int                 $priority  Event priority to use for all $listeners. This will default to 1.
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function addListenersForEvent($event, iterable $listeners, int $priority = 1): void
    {
        $this->listenerProvider->addListenersForEvent($event, $listeners, $priority);
    }

    /**
     * Check if the event propagation has been stopped.
     *
     * @param object $event
     *
     * @return bool
     */
    private function isPropagationStopped(object $event): bool
    {
        return ($event instanceof StoppableEventInterface && $event->isPropagationStopped());
    }
}
