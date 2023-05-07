<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;

interface AddListenerAwareInterface
{
    /**
     * @throws EventListenerException
     */
    public function addListenerForEvent(object|string $event, callable $listener, int $priority = 1): void;

    /**
     * @throws EventListenerException
     */
    public function addListenersForEvent(object|string $event, iterable $listeners, int $priority = 1): void;
}
