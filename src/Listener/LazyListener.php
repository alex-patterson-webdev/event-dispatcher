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
    private $arguments = [];

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
        $this->factory   = $factory;
    }

    /**
     * Create and then execute the event listener.
     *
     * @param object $event
     */
    public function __invoke(object $event)
    {
        $listener = call_user_func_array(
            (isset($this->factory) ? $this->factory : [$this, 'createListener']),
            [$this->className, $this->arguments]
        );

        $listener($event);
    }

    /**
     * Create the event listener.
     *
     * @param string $className
     * @param array  $arguments
     *
     * @return callable
     */
    protected function createListener(string $className, array $arguments = []) : callable
    {
        return new $className(...$arguments);
    }

}