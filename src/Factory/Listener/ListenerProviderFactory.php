<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Factory\Listener;

use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Factory\Listener
 */
final class ListenerProviderFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $defaultClassName = ListenerProvider::class;

    /**
     * Create a new service.
     *
     * @param array $config The optional factory configuration options.
     *
     * @return ListenerProviderInterface
     *
     * @throws FactoryException If the listener provider cannot be created.
     */
    public function create(array $config = []): ListenerProviderInterface
    {
        $className = $config['class_name'] ?? $this->defaultClassName;
        $eventNameResolver = $config['event_name_resolver'] ?? null;

        if (null === $eventNameResolver) {
            $eventNameResolver = EventNameResolver::class;
        }

        if (is_string($eventNameResolver)) {
            if (! is_a($eventNameResolver, EventNameResolverInterface::class, true)) {
                throw new FactoryException(sprintf(
                    'The event name resolver must be a class that implements \'%s\'; \'%s\' provided in \'%s\'.',
                    EventNameResolverInterface::class,
                    $eventNameResolver,
                    static::class
                ));
            }

            $eventNameResolver = new $eventNameResolver();
        }

        if (! $eventNameResolver instanceof EventNameResolverInterface) {
            throw new FactoryException(sprintf(
                'The event name resolver must be an object that implements \'%s\'; \'%s\' provided in \'%s\'.',
                EventNameResolverInterface::class,
                is_object($eventNameResolver) ? get_class($eventNameResolver) : gettype($eventNameResolver),
                static::class
            ));
        }

        return new $className($eventNameResolver);
    }
}
