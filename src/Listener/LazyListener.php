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
     * @var callable|null
     */
    private $factory;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param callable $factory
     * @param array    $arguments
     */
    public function __construct(callable $factory, array $arguments = [])
    {
        $this->factory = $factory;
        $this->arguments = $arguments;
    }

    /**
     * Create and then execute the event listener.
     *
     * @param object $event The event that has been dispatched.
     *
     * @throws EventListenerException  If the loaded event listener is not callable.
     */
    public function __invoke(object $event): void
    {
        $listener = $this->loadListener($event);
        $listener($event);
    }

    /**
     * @return callable
     */
    private function loadListener(): callable
    {
        $factory = $this->factory ?? $this->getDefaultListenerFactory();
        return $factory($this->arguments);
    }

    /**
     * Return the default event listener factory.
     *
     * @return \Closure
     */
    protected function getDefaultListenerFactory(): callable
    {
        return static function (string $className, array $arguments = []) {
            return new $className($arguments);
        };
    }
}
