<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Psr\EventDispatcher\ListenerProviderInterface;

final class EventDispatcher extends AbstractEventDispatcher implements AddListenerAwareInterface
{
    public function __construct(private readonly AddableListenerProviderInterface $listenerProvider)
    {
    }

    /**
     * @throws EventListenerException
     */
    public function addListenerForEvent(object|string $event, callable $listener, int $priority = 1): void
    {
        $this->listenerProvider->addListenerForEvent($event, $listener, $priority);
    }

    /**
     * @throws EventListenerException
     */
    public function addListenersForEvent(string|object $event, iterable $listeners, int $priority = 1): void
    {
        $this->listenerProvider->addListenersForEvent($event, $listeners, $priority);
    }

    /**
     * @return ListenerProviderInterface
     */
    protected function getListenerProvider(): ListenerProviderInterface
    {
        return $this->listenerProvider;
    }
}
