<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

class ListenerCollection implements ListenerCollectionInterface
{
    private PriorityQueue $listeners;

    private int $queueOrder = PHP_INT_MAX;

    public function __construct(iterable $listeners = [])
    {
        $this->listeners = new PriorityQueue();

        if (!empty($listeners)) {
            $this->addListeners($listeners);
        }
    }

    public function addListeners(iterable $listeners, int $priority = 1): void
    {
        foreach ($listeners as $listener) {
            $this->addListener($listener, $priority);
        }
    }

    public function addListener(callable $listener, int $priority = 1): void
    {
        $this->listeners->insert($listener, [$priority, $this->queueOrder--]);
    }

    public function getIterator(): \Traversable
    {
        $iterator = clone $this->listeners;
        $iterator->rewind();

        return $iterator;
    }

    public function count(): int
    {
        return $this->listeners->count();
    }
}
