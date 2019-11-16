<?php

namespace Arp\EventDispatcher\Listener;

/**
 * ListenerCollectionInterface
 *
 * @package Arp\EventDispatcher\Listener
 */
interface ListenerCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * Add a single listener to the collection.
     *
     * @param callable $listener   The listener that should be attached.
     * @param int      $priority   Optional priority for the listener.
     */
    public function addListener(callable $listener, int $priority = 1) : void;

    /**
     * Add a collection of listeners to the collection.
     *
     * @param callable[] $listeners
     */
    public function addListeners($listeners) : void;
}
