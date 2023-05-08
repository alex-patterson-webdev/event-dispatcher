<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;

class LazyListener
{
    private object $factory;

    private string $factoryMethodName = '__invoke';

    private string $listenerMethodName = '__invoke';

    public function __construct(
        callable|object $factory,
        ?string $factoryMethodName = null,
        ?string $listenerMethodName = null,
    ) {
        if (is_callable($factory)) {
            $this->factory = $factory(...);
        } elseif (is_object($factory)) {
            $this->factory = $factory;
        }

        $this->factoryMethodName = $factoryMethodName ?? $this->factoryMethodName;
        $this->listenerMethodName = $listenerMethodName ?? $this->listenerMethodName;
    }

    /**
     * @throws EventListenerException
     */
    public function __invoke(object $event): mixed
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
