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
            return $this->getOrCreateListenerCollection($event);
        } catch (InvalidArgumentException $e) {
        }

        return $this->createListenerCollection();
    }

    /**
     * Add a new event listener to the collection.
     *
     * @param object|string  $event     The event that should be attached to.
     * @param callable       $listener  The event listener to attach.
     * @param int            $priority  The event priority.
     *
     * @throws InvalidArgumentException  If the $event argument is of an invalid type.
     */
    public function addListenerForEvent($event, callable $listener, int $priority = 1) : void
    {
        $this->getOrCreateListenerCollection($event)->addListener($listener, $priority);
    }

    /**
     * Add a collection of event listeners for a single event.
     *
     * @param object|string       $event      The event name or instance to attach to.
     * @param iterable|callable[] $listeners  Collection of listeners to attach.
     *
     * @throws InvalidArgumentException  If the $event argument is of an invalid type.
     */
    public function addListenersForEvent($event, iterable $listeners) : void
    {
        $collection = $this->getOrCreateListenerCollection($event);

        if ($listeners instanceof \Traversable) {
            $collection->merge($listeners);
        } else {
            $collection->addListeners($listeners);
        }
    }

    /**
     * Return a listener collection matching the provided $eventName. If no collection can be found a new
     * empty one will be created and assigned to the $collections array using the $eventName as the key.
     *
     * @param string|object $event  The name or instance of the event.
     *
     * @return ListenerCollectionInterface
     *
     * @throws InvalidArgumentException  If the $event argument is of an invalid type.
     */
    private function getOrCreateListenerCollection($event) : ListenerCollectionInterface
    {
        $eventName = $this->eventNameResolver->resolveEventName($event);

        if (! isset($this->collections[$eventName])) {
            $this->collections[$eventName] = $this->createListenerCollection();
        }

        return $this->collections[$eventName];
    }

    /**
     * Create a new listener collection with optional $listeners.
     *
     * @param callable[] $listeners  The optional event listeners that should be added.
     *
     * @return ListenerCollection
     */
    protected function createListenerCollection(array $listeners = []) : ListenerCollectionInterface
    {
        return new ListenerCollection($listeners);
    }
}
