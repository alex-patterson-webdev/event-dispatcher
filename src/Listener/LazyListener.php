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
     * @param callable|object|\Closure $factory
     * @param string|null              $factoryMethodName
     * @param string|null              $listenerMethodName
     *
     * @throws EventListenerException
     */
    public function __construct(
        $factory,
        ?string $factoryMethodName = null,
        ?string $listenerMethodName = null
    ) {
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
        if (!$this->factory instanceof \Closure && !is_callable([$this->factory, $this->factoryMethodName])) {
            throw new EventListenerException(
                sprintf(
                    'The factory method \'%s\' is not callable for lazy loaded listener of type \'%s\'',
                    $this->factoryMethodName,
                    is_object($this->factory) ? get_class($this->factory) : gettype($this->factory)
                )
            );
        }

        if ($this->factory instanceof \Closure) {
            $listener = ($this->factory)();
        } else {
            $listener = call_user_func([$this->factory, $this->factoryMethodName]);
        }

        if (!$listener instanceof \Closure && !is_callable([$listener, $this->listenerMethodName])) {
            throw new EventListenerException(
                sprintf(
                    'The listener method \'%s\' is not callable for lazy loaded listener of type \'%s\'',
                    $this->listenerMethodName,
                    is_object($listener) ? get_class($listener) : gettype($listener)
                )
            );
        }

        if ($listener instanceof \Closure) {
            return $listener($event);
        }

        return call_user_func([$listener, $this->listenerMethodName], $event);
    }
}
