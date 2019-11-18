<?php

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Exception\InvalidArgumentException;
use ArpTest\EventDispatcher\Listener\TestQueue;

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
     * Internal pointer to the queue order.
     *
     * @var int
     */
    private $queueOrder = PHP_INT_MIN;

    /**
     * __construct
     *
     * @param mixed $listeners
     *
     * @throws InvalidArgumentException
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
     *
     * @throws InvalidArgumentException  If the $listeners argument is of an invalid type.
     */
    public function addListeners($listeners) : void
    {
        if (! is_array($listeners) && ! $listeners instanceof \Traversable) {

            throw new InvalidArgumentException(sprintf(
                'The \'listeners\' argument must be an \'array\' or object of type \'%s\'; \'%s\' provided in \'%s\'.',
                \Traversable::class,
                gettype($listeners),
                __METHOD__
            ));
        }

        foreach($listeners as $listener) {
            $this->addListener($listener);
        }
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
