<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Factory\Listener;

use Arp\EventDispatcher\Factory\ListenerRegistrationTrait;
use Arp\EventDispatcher\Factory\Resolver\EventNameResolverFactory;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Factory\Listener
 */
class ListenerProviderFactory implements FactoryInterface
{
    use ListenerRegistrationTrait;

    /**
     * @var FactoryInterface
     */
    private $eventNameResolverFactory;

    /**
     * @param FactoryInterface|null $eventNameResolverFactory
     */
    public function __construct(FactoryInterface $eventNameResolverFactory = null)
    {
        $this->eventNameResolverFactory = $eventNameResolverFactory;
    }

    /**
     * Create a new listener provider.
     *
     * @param array $config
     *
     * @return ListenerProviderInterface
     *
     * @throws FactoryException
     */
    public function create(array $config = []): ListenerProviderInterface
    {
        $eventNameResolver = $this->createEventNameResolver($config['event_name_resolver'] ?? null);

        $listenerProvider = new ListenerProvider($eventNameResolver);

        $listeners = $config['listeners'] ?? [];
        if (is_array($listeners) && $listenerProvider instanceof AddableListenerProviderInterface) {
            $this->registerEventListeners($listenerProvider, $listeners);
        }

        return $listenerProvider;
    }

    /**
     * Create a new event name resolver based on the provided $config.
     *
     * @param array|EventNameResolverInterface $config
     *
     * @return EventNameResolverInterface
     *
     * @throws FactoryException
     */
    private function createEventNameResolver($config): EventNameResolverInterface
    {
        if ($config instanceof EventNameResolverInterface) {
            return $config;
        }

        if (null === $this->eventNameResolverFactory) {
            $this->eventNameResolverFactory = new EventNameResolverFactory();
        }

        return $this->eventNameResolverFactory->create($config);
    }
}
