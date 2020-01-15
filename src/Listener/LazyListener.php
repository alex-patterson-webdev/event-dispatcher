<?php

namespace Arp\EventDispatcher\Listener;

/**
 * LazyListener
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
class LazyListener
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var callable|null
     */
    private $factory;

    /**
     * __construct
     *
     * @param string        $className
     * @param array         $arguments
     * @param callable|null $factory
     */
    public function __construct(
        string $className,
        array $arguments = [],
        callable $factory = null
    ){
        $this->className = $className;
        $this->arguments = $arguments;
        $this->factory = $factory;
    }

    /**
     * Create and then execute the event listener.
     *
     * @param object $event
     */
    public function __invoke(object $event) : void
    {
        $factory = $this->factory;

        if (null === $factory) {
            $factory = $this->getDefaultListenerFactory();
        }

        $listener = $factory($this->className, $this->arguments);
        $listener($event);
    }

    /**
     * Return the default event listener factory.
     *
     * @return \Closure
     */
    protected function getDefaultListenerFactory() : callable
    {
        return static function(string $className, array $arguments = []) {
            return new $className(...$arguments);
        };
    }
}
