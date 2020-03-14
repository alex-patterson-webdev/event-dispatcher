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
     * @param callable[] $listeners
     */
    public function __construct($listeners = [])
    {
        $this->listeners = new \SplPriorityQueue();

        if (! empty($listeners)) {
            $this->addListeners($listeners);
        }
    }

    /**
     * Return the listener iterator
     *
     * @return \Traversable
     */
    public function getIterator() : \Traversable
    {
        $clone = clone $this->listeners;

        $clone->rewind();

        return $clone;
    }

    /**
     * Add a single listener to the collection.
     *
     * @param callable $listener   The listener that should be attached.
     * @param int      $priority   Optional priority for the listener.
     */
    public function addListener(callable $listener, int $priority = 1) : void
    {
        $this->listeners->insert($listener, [$priority, $this->queueOrder++]);
    }

    /**
     * Add a collection of listeners to the collection.
     *
     * @param callable[] $listeners  The collection of event listeners to add.
     * @param int        $priority   Optional priority for the listener.
     */
    public function addListeners(array $listeners, int $priority = 1) : void
    {
        foreach ($listeners as $listener) {
            $this->addListener($listener, $priority);
        }
    }

    /**
     * Merge the provided collection into the current one.
     *
     * @param \Traversable $collection The collection that should be merged.
     * @param int          $priority   Optional priority for the listener.
     */
    public function merge(\Traversable $collection, int $priority = 1) : void
    {
        $this->addListeners(iterator_to_array($collection, false), $priority);
    }

    /**
     * Return the number of records in the collection.
     *
     * @return int
     */
    public function count() : int
    {
        return $this->listeners->count();
    }
}
