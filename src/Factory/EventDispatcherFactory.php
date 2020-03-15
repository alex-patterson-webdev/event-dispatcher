<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Factory;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Factory
 */
final class EventDispatcherFactory implements FactoryInterface
{
    /**
     * The default class that will be instantiated, without a 'class_name' configuration option.
     *
     * @var string
     */
    private $defaultClassName = EventDispatcher::class;

    /**
     * @var FactoryInterface
     */
    private $listenerProviderFactory;

    /**
     * @param FactoryInterface|null $listenerProviderFactory
     * @param string|null $defaultClassName
     */
    public function __construct(
        FactoryInterface $listenerProviderFactory = null,
        string $defaultClassName = null
    ) {
        if (null === $listenerProviderFactory) {
            $listenerProviderFactory = new ListenerProviderFactory();
        }

        $this->listenerProviderFactory = $listenerProviderFactory;

        if (null !== $defaultClassName) {
            $this->defaultClassName = $defaultClassName;
        }
    }

    /**
     * Create a new EventDispatcher using the provided $config options.
     *
     * @param array $config The optional factory configuration options.
     *
     * @return EventDispatcherInterface
     *
     * @throws FactoryException If the service cannot be created.
     */
    public function create(array $config = []): EventDispatcherInterface
    {
        $className = $config['class_name'] ?? $this->defaultClassName;
        $listenerProvider = $config['listener_provider'] ?? [];

        if (is_array($listenerProvider)) {
            $listenerProvider = $this->listenerProviderFactory->create($listenerProvider);
        }

        if (! $listenerProvider instanceof ListenerProviderInterface) {
            throw new FactoryException(sprintf(
                'The \'listener_provider\' configuration option must be of type \'%s\'; \'%s\' provided in \'%s\'',
                ListenerProviderInterface::class,
                is_object($listenerProvider) ? get_class($listenerProvider) : gettype($listenerProvider),
                static::class
            ));
        }

        return new $className($listenerProvider);
    }
}
