<?php

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Exception\InvalidArgumentException;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * ListenerProvider
 *
 * @package Arp\EventDispatcher\Listener
 */
class ListenerProvider implements ListenerProviderInterface
{
    /**
     * Collection of priority queue's for each event collection.
     *
     * @var ListenerCollectionInterface[]
     */
    protected $collections = [];

    /**
     * Service used to resolve the name of an event.
     *
     * @var EventNameResolverInterface
     */
    protected $eventNameResolver;

    /**
     * @param EventNameResolverInterface $eventNameResolver
     */
    public function __construct(EventNameResolverInterface $eventNameResolver)
    {
        $this->eventNameResolver = $eventNameResolver;
    }



    /**
     * Return a iterator with a collection of listeners for the provided event.
     *
     * @param object $event The event that will be triggered.
     *
     * @return callable[] A collection of event listeners to execute.
     */
    public function getListenersForEvent(object $event) : iterable
    {
        try {
            $eventName = $this->eventNameResolver->resolveEventName($event);
        }
        catch (InvalidArgumentException $e) {
        }

        if (empty($eventName) || ! isset($this->listeners[$eventName])) {
            // Return an empty collection
            return $this->createListenerCollection();
        }

        return $this->collections[$eventName];
    }

    /**
     * Add a new event listener to the collection.
     *
     * @param object|string  $event     The event that should be attached to.
     * @param callable       $listener  The event listener to attach.
     * @param int            $priority  The event priority.
     *
     * @throws InvalidArgumentException
     */
    public function addListenerForEvent($event, callable $listener, int $priority = 1) : void
    {
        $this->getListenerCollection($event)->addListener($listener, $priority);
    }

    /**
     * Add a collection of event listeners for a single event.
     *
     * @param object|string  $event      The event name or instance to attach to.
     * @param callable[]     $listeners  Collection of listeners to attach.
     *
     * @throws InvalidArgumentException
     */
    public function addListenersForEvent($event, $listeners) : void
    {
        $this->getListenerCollection($event)->addListeners($listeners);
    }

    /**
     * Return the event collection for a given event instance or name. If the collection doesn't exist a new
     * empty one will be created.
     *
     * @param object|string  $event  The event object or name.
     *
     * @return ListenerCollectionInterface
     *
     * @throws InvalidArgumentException
     */
    private function getListenerCollection($event) : ListenerCollectionInterface
    {
        $eventName = $this->eventNameResolver->resolveEventName($event);

        if (empty($this->collections[$eventName])) {
            $this->collections[$eventName] = $this->createListenerCollection();
        }

        return $this->collections[$eventName];
    }

    /**
     * Create a new listener collection.
     *
     * @param array $listeners  Optional collection of listeners to add to the collection.
     *
     * @return ListenerCollectionInterface
     */
    protected function createListenerCollection(array $listeners = []) : ListenerCollectionInterface
    {
        return new ListenerCollection($listeners);
    }
}
