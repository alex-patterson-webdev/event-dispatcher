<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

final class ImmutableEventDispatcher extends AbstractEventDispatcher
{
    public function __construct(private readonly ListenerProviderInterface $listenerProvider)
    {
    }

    protected function getListenerProvider(): ListenerProviderInterface
    {
        return $this->listenerProvider;
    }
}
