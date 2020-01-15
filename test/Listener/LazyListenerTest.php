<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\LazyListener;
use Arp\EventDispatcher\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * LazyListenerTest
 *
 * @package ArpTest\EventDispatcher\Listener
 */
final class LazyListenerTest extends TestCase
{
    /**
     * testIsCallable
     *
     * @test
     */
    public function testIsCallable() : void
    {
        $listener = new LazyListener(\stdClass::class, []);

        $this->assertIsCallable($listener);
    }

    /**
     * Assert that a new RuntimeException will be thrown if the lazy loaded event listener is not callable.
     *
     * @test
     */
    public function testInvokeWillThrowRuntimeExceptionIfLoadedListenerIsNotCallable() : void
    {
        $className = \stdClass::class;

        $factory = static function () {
            return false; // our result is not a callable type.
        };

        $listener = new LazyListener($className, [], $factory);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'The the lazy loaded event listener, using class \'%s\', is not callable.',
            $className
        ));

        $event = new \stdClass();

        $listener($event);
    }

    /**
     * Assert that the event listener will be created and invoked.
     *
     * @test
     */
    public function testInvokeWillCreateAndInvokeTheLazyEventListener() : void
    {
        $event = new \stdClass();

        $mockedListener = function ($passedEvent) use ($event)  {
            $this->assertSame($passedEvent, $event);
        };

        $factory = static function ($event) use ($mockedListener) {
            return $mockedListener;
        };

        $listener = new LazyListener('Foo', [], $factory);

        $listener($event);
    }
}
