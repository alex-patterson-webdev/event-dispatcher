<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
final class LazyListenerConfig extends ListenerConfig
{
    /**
     * @var string|FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $options;

    /**
     * @param FactoryInterface|string $factory
     * @param string                  $eventName
     * @param int                     $priority
     * @param array                   $options
     *
     * @throws EventListenerException
     */
    public function __construct($factory, string $eventName, int $priority = 1, array $options = [])
    {
        if (!$factory instanceof FactoryInterface && !is_a($factory, FactoryInterface::class, true)) {
            throw new EventListenerException(
                sprintf(
                    'The \'listener\' argument must be a \'string\' or an object of type \'%s\'; '
                    . '\'%s\' provided in \'%s\'',
                    FactoryInterface::class,
                    is_object($factory) ? get_class($factory) : gettype($factory),
                    static::class
                )
            );
        }

        $this->factory = $factory;
        $this->options = $options;

        $listener = static function () {
        };

        parent::__construct($listener, $eventName, $priority);
    }

    /**
     * @return callable
     *
     * @throws EventListenerException
     */
    public function getListener(): callable
    {
        $listener = $this->listener;
        $factory = $this->factory;

        try {
            if (is_string($factory)) {
                $this->factory = $factory = new $factory();
            }

            if ($factory instanceof FactoryInterface) {
                $this->listener = $listener = $factory->create($this->options);
            }
        } catch (FactoryException $e) {
            throw new EventListenerException(
                sprintf(
                    'Failed to lazy load the event listener from factory class \'%s\': %s',
                    get_class($factory),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        return $listener;
    }
}
