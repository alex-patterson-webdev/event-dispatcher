<?php

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Exception\InvalidArgumentException;

/**
 * ListenerCollection
 *
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
     * __construct
     *
     * @param callable[] $listeners
     */
    public function __construct($listeners = [])
    {
        $this->listeners = new \SplPriorityQueue;

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
     */
    public function addListeners(array $listeners) : void
    {
        foreach($listeners as $listener) {
            $this->addListener($listener);
        }
    }

    /**
     * Merge the provided collection into the current one.
     *
     * @param \Traversable $collection The collection that should be merged.
     */
    public function merge(\Traversable $collection) : void
    {
        $this->addListeners(iterator_to_array($collection, false));
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
