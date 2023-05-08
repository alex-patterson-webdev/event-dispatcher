<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;

class ListenerProvider implements AddableListenerProviderInterface
{
    /**
     * @var array<ListenerCollectionInterface>
     */
    protected array $collections = [];

    protected EventNameResolverInterface $eventNameResolver;

    public function __construct(?EventNameResolverInterface $eventNameResolver = null)
    {
        $this->eventNameResolver = $eventNameResolver ?? new EventNameResolver();
    }

    /**
     * @return iterable<callable>&ListenerCollectionInterface
     *
     * @throws EventListenerException
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->getOrCreateListenerCollection($event);
    }

    /**
     * @throws EventListenerException
     */
    public function addListenerForEvent(object|string $event, callable $listener, int $priority = 1): void
    {
        $this->getOrCreateListenerCollection($event)->addListener($listener, $priority);
    }

    /**
     * @throws EventListenerException  If the $event name cannot be resolved.
     */
    public function addListenersForEvent(object|string $event, iterable $listeners, int $priority = 1): void
    {
        $this->getOrCreateListenerCollection($event)->addListeners($listeners, $priority);
    }

    /**
     * @param array<callable> $listeners
     *
     * @return ListenerCollectionInterface<callable>
     */
    protected function createListenerCollection(array $listeners = []): ListenerCollectionInterface
    {
        return new ListenerCollection($listeners);
    }

    /**
     * @throws EventListenerException
     */
    private function getOrCreateListenerCollection(string|object $event): ListenerCollectionInterface
    {
        try {
            $eventName = $this->eventNameResolver->resolveEventName($event);
        } catch (EventNameResolverException $e) {
            throw new EventListenerException(
                sprintf('Failed to resolve the event name : %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

        if (!isset($this->collections[$eventName])) {
            $this->collections[$eventName] = $this->createListenerCollection();
        }

        return $this->collections[$eventName];
    }
}
