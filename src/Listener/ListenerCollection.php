<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
class ListenerCollection implements ListenerCollectionInterface
{
    /**
     * The collection of listeners to iterate.
     *
     * @var PriorityQueue|callable[]
     */
    private $listeners;

    /**
     * Internal count for the queue priority order.
     *
     * @var int
     */
    private $queueOrder = PHP_INT_MAX;

    /**
     * @param iterable|callable[] $listeners
     */
    public function __construct(iterable $listeners = [])
    {
        $this->listeners = new PriorityQueue();

        if (!empty($listeners)) {
            $this->addListeners($listeners);
        }
    }

    /**
     * Add a collection of listeners to the collection.
     *
     * @param callable[] $listeners The collection of event listeners to add.
     * @param int        $priority  Optional priority for the listener.
     */
    public function addListeners(iterable $listeners, int $priority = 1): void
    {
        foreach ($listeners as $listener) {
            $this->addListener($listener, $priority);
        }
    }

    /**
     * Add a single listener to the collection.
     *
     * @param callable $listener The listener that should be attached.
     * @param int      $priority Optional priority for the listener.
     */
    public function addListener(callable $listener, int $priority = 1): void
    {
        $this->listeners->insert($listener, [$priority, $this->queueOrder--]);
    }

    /**
     * Return the listener iterator
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        $iterator = clone $this->listeners;
        $iterator->rewind();

        return $iterator;
    }

    /**
     * Return the number of records in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->listeners->count();
    }
}
