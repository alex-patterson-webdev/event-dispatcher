<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * ListenerCollectionInterface
 *
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
     * @param callable[] $listeners The collection of event listeners to add.
     * @param int        $priority  Optional priority for the listener.
     */
    public function addListeners(array $listeners, int $priority = 1): void;

    /**
     * Merge the provided collection into the current one.
     *
     * @param \Traversable $collection The collection that should be merged.
     * @param int          $priority   Optional priority for the listener.
     */
    public function merge(\Traversable $collection, int $priority = 1): void;
}
