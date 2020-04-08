<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
interface ListenerCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * Add a single listener to the collection.
     *
     * @param callable $listener The listener that should be attached.
     * @param int      $priority Optional priority for the listener.
     */
    public function addListener(callable $listener, int $priority = 1): void;

    /**
     * Add a collection of listeners to the collection.
     *
     * @param iterable|callable[] $listeners The collection of event listeners to add.
     * @param int                 $priority  Optional priority for the listener.
     */
    public function addListeners(iterable $listeners, int $priority = 1): void;
}
