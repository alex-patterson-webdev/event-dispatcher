<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher
 */
final class EventDispatcher extends AbstractEventDispatcher implements AddListenerAwareInterface
{
    /**
     * @var AddableListenerProviderInterface
     */
    protected $listenerProvider;

    /**
     * @param AddableListenerProviderInterface $listenerProvider
     */
    public function __construct(AddableListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * Add a new event listener to the collection.
     *
     * @param object|string $event    The event that should be attached to.
     * @param callable      $listener The event listener to attach.
     * @param int           $priority The event priority.
     *
     * @throws EventListenerException  If the event listener cannot be added.
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
     * @throws EventListenerException  If the event listeners cannot be added.
     */
    public function addListenersForEvent($event, iterable $listeners, int $priority = 1): void
    {
        $this->listenerProvider->addListenersForEvent($event, $listeners, $priority);
    }
}
