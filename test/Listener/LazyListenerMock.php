<?php

namespace ArpTest\EventDispatcher\Listener;

use PHPUnit\Framework\Assert;

/**
 * Test listener class that we can get the LazyListener to create within our testing.
 *
 * @author Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener
 */
final class LazyListenerMock
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    /**
     * __invoke
     *
     * @param object $event
     */
    public function __invoke(object $event)
    {
        // so hacky :-/
        if (isset($this->arguments[0]) && is_object($this->arguments[0])) {
            Assert::assertSame($this->arguments[0], $event);
        }
    }
}
