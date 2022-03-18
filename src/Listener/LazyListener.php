<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
class LazyListener
{
    /**
     * @var object|\Closure
     */
    private $factory;

    /**
     * @var string
     */
    private string $factoryMethodName = '__invoke';

    /**
     * @var string
     */
    private string $listenerMethodName = '__invoke';

    /**
     * @param callable|object|\Closure|mixed $factory
     * @param string|null                    $factoryMethodName
     * @param string|null                    $listenerMethodName
     *
     * @throws EventListenerException
     */
    public function __construct($factory, ?string $factoryMethodName = null, ?string $listenerMethodName = null)
    {
        if (is_callable($factory)) {
            $this->factory = \Closure::fromCallable($factory);
        } elseif (is_object($factory)) {
            $this->factory = $factory;
        } else {
            throw new EventListenerException(
                sprintf(
                    'The event listener factory must be of type \'callable\' or \'object\'; \'%s\' provided in \'%s\'',
                    is_object($factory) ? get_class($factory) : gettype($factory),
                    static::class
                )
            );
        }

        $this->factoryMethodName = $factoryMethodName ?? $this->factoryMethodName;
        $this->listenerMethodName = $listenerMethodName ?? $this->listenerMethodName;
    }

    /**
     * Create and then execute the event listener.
     *
     * @param object $event The event that has been dispatched
     *
     * @return mixed
     *
     * @throws EventListenerException
     */
    public function __invoke(object $event)
    {
        $factory = is_callable($this->factory)
            ? $this->factory
            : [$this->factory, $this->factoryMethodName];

        if (is_callable($factory)) {
            $listener = $factory();
        } else {
            throw new EventListenerException(
                sprintf(
                    'The method \'%s\' is not callable for lazy load factory \'%s\'',
                    $this->factoryMethodName,
                    gettype($factory)
                )
            );
        }

        $listener = is_callable($listener) ? $listener : [$listener, $this->listenerMethodName];
        if (!is_callable($listener)) {
            throw new EventListenerException(
                sprintf(
                    'The method \'%s\' is not callable for lazy load event listener \'%s\'',
                    $this->listenerMethodName,
                    gettype($listener)
                )
            );
        }

        return $listener($event);
    }
}
