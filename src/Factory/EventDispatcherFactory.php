<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Factory;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Factory
 */
class EventDispatcherFactory implements FactoryInterface
{
    use ListenerRegistrationTrait;

    /**
     * @var FactoryInterface|null
     */
    private $listenerProviderFactory;

    /**
     * @param FactoryInterface|null $listenerProviderFactory
     */
    public function __construct(?FactoryInterface $listenerProviderFactory = null)
    {
        $this->listenerProviderFactory = $listenerProviderFactory;
    }

    /**
     * Create a new event dispatcher.
     *
     * @param array $config
     *
     * @return EventDispatcherInterface
     *
     * @throws FactoryException
     */
    public function create(array $config = []): EventDispatcherInterface
    {
        $eventDispatcher = new EventDispatcher(
            $this->createListenerProvider($config['listener_provider'] ?? [])
        );

        $listeners = $config['listeners'] ?? [];
        if (!empty($listeners) && $eventDispatcher instanceof AddListenerAwareInterface) {
            $this->registerEventListeners($eventDispatcher, $listeners);
        }

        return $eventDispatcher;
    }

    /**
     * Create a new listener provider with the provided configuration options.
     *
     * @param ListenerProviderInterface|array $config
     *
     * @return AddableListenerProviderInterface
     *
     * @throws FactoryException
     */
    private function createListenerProvider($config): AddableListenerProviderInterface
    {
        if ($config instanceof AddableListenerProviderInterface) {
            return $config;
        }

        if (!is_array($config)) {
            throw new FactoryException(
                sprintf(
                    'The listener provider configuration must be of type \'array\'; \'%s\' provided in \'%s\'',
                    gettype($config),
                    __METHOD__
                )
            );
        }

        if (null === $this->listenerProviderFactory) {
            $this->listenerProviderFactory = new ListenerProviderFactory();
        }

        /** @var AddableListenerProviderInterface $listenerProvider */
        return $this->listenerProviderFactory->create($config);
    }
}
