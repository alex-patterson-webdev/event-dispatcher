<?php

declare(strict_types=1);

namespace Arp\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * An event dispatcher that does not expose any methods that are able to modify or change the listener provider
 * that is passed in during construction.
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher
 */
final class ImmutableEventDispatcher extends AbstractEventDispatcher
{
    /**
     * @var ListenerProviderInterface
     */
    private ListenerProviderInterface $listenerProvider;

    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * @return ListenerProviderInterface
     */
    protected function getListenerProvider(): ListenerProviderInterface
    {
        return $this->listenerProvider;
    }
}
