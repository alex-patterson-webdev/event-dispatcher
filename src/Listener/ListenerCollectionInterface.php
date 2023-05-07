<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @extends \IteratorAggregate<mixed, mixed>
 */
interface ListenerCollectionInterface extends \IteratorAggregate, \Countable
{
    public function addListener(callable $listener, int $priority = 1): void;

    public function addListeners(iterable $listeners, int $priority = 1): void;
}
