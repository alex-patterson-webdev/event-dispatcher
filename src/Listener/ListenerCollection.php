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
     * @var \SplPriorityQueue|callable[]
     */
    private $listeners;

    /**
     * Internal count for the queue priority order.
     *
     * @var int
     */
    private $queueOrder = PHP_INT_MIN;

    /**
     * @param iterable|callable[] $listeners
     */
    public function __construct(iterable $listeners = [])
    {
        $this->listeners = new \SplPriorityQueue();

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
        $this->listeners->insert($listener, [$priority, $this->queueOrder++]);
    }

    /**
     * Return the listener iterator
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        $clone = clone $this->listeners;

        $clone->rewind();

        return $clone;
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
