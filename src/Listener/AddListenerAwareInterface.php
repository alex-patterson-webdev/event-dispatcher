<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
interface AddListenerAwareInterface
{
    /**
     * Add a new event listener to the collection.
     *
     * @param object|string $event    The event that should be attached to.
     * @param callable      $listener The event listener to attach.
     * @param int           $priority The event priority.
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function addListenerForEvent($event, callable $listener, int $priority = 1): void;

    /**
     * Add a collection of event listeners for a single event.
     *
     * @param object|string       $event     The event name or instance to attach to.
     * @param iterable|callable[] $listeners Collection of listeners to attach.
     * @param int                 $priority  Event priority to use for all $listeners. This will default to 1.
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function addListenersForEvent($event, iterable $listeners, int $priority = 1): void;
}
