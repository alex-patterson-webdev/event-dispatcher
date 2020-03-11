<?php declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * ListenerProvider
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
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
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function getListenersForEvent(object $event) : iterable
    {
        return $this->getOrCreateListenerCollection($event);
    }

    /**
     * Add a new event listener to the collection.
     *
     * @param object|string  $event     The event that should be attached to.
     * @param callable       $listener  The event listener to attach.
     * @param int            $priority  The event priority.
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
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
     * @param int                 $priority   Event priority to use for all $listeners. This will default to 1.
     *
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function addListenersForEvent($event, iterable $listeners, int $priority = 1) : void
    {
        $collection = $this->getOrCreateListenerCollection($event);

        if ($listeners instanceof \Traversable) {
            $collection->merge($listeners, $priority);
        } else {
            $collection->addListeners($listeners, $priority);
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
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    private function getOrCreateListenerCollection($event) : ListenerCollectionInterface
    {
        try {
            $eventName = $this->eventNameResolver->resolveEventName($event);
        }
        catch (EventNameResolverException $e) {

            throw new EventListenerException(
                sprintf('Failed to resolve the event name : %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

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
