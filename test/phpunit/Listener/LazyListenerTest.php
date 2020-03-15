<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Listener\LazyListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener
 */
final class LazyListenerTest extends TestCase
{
    /**
     * testIsCallable
     *
     * @covers \Arp\EventDispatcher\Listener\LazyListener::__construct
     */
    public function testIsCallable(): void
    {
        $listener = new LazyListener(\stdClass::class, []);

        $this->assertIsCallable($listener);
    }

    /**
     * Assert that a new EventListenerException will be thrown if the lazy loaded event listener is not callable.
     *
     * @covers \Arp\EventDispatcher\Listener\LazyListener::__invoke
     */
    public function testInvokeWillThrowEventListenerExceptionIfLoadedListenerIsNotCallable(): void
    {
        $className = \stdClass::class;

        $factory = static function () {
            return false; // our result is not a callable type.
        };

        $listener = new LazyListener($className, [], $factory);

        $this->expectException(EventListenerException::class);
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
     * @covers \Arp\EventDispatcher\Listener\LazyListener::__invoke
     */
    public function testInvokeWillCreateAndInvokeTheLazyEventListener(): void
    {
        $expectedClassName = \stdClass::class;

        $expectedEvent = new \stdClass();
        $expectedArgs = ['hello' => 'foo'];

        $mockedListener = function ($passedEvent) use ($expectedEvent) {
            $this->assertSame($passedEvent, $expectedEvent);
        };

        $factory = function ($className, $arguments) use ($mockedListener, $expectedArgs, $expectedClassName) {
            $this->assertSame($expectedClassName, $className);
            $this->assertSame($expectedArgs, $arguments);
            return $mockedListener;
        };

        $lazyListener = new LazyListener($expectedClassName, $expectedArgs, $factory);

        $lazyListener($expectedEvent);
    }

    /**
     * Assert that the default factory will be used if no factory has been provided to the.
     *
     * @throws EventListenerException
     *
     * @covers \Arp\EventDispatcher\Listener\LazyListener::__invoke
     */
    public function testDefaultFactoryWillBeUsedWhenOneIsNotProvidedViaConstruct(): void
    {
        $expectedClassName = 'Foo';
        $expectedArguments = ['foo' => 'bar', 'bar' => 123];

        $expectedEvent = new \stdClass();

        $defaultListener = function (object $event) use ($expectedEvent) {
            $this->assertSame($expectedEvent, $event);
        };

        $defaultListenerFactory = function (
            string $className,
            array $arguments = []
        ) use (
            $expectedClassName,
            $expectedArguments,
            $defaultListener
        ) {
            $this->assertSame($expectedClassName, $className);
            $this->assertSame($expectedArguments, $arguments);

            return $defaultListener;
        };

        /** @var LazyListener|MockObject $lazyListener */
        $lazyListener = $this->getMockBuilder(LazyListener::class)
            ->setConstructorArgs([$expectedClassName, $expectedArguments])
            ->onlyMethods(['getDefaultListenerFactory'])
            ->getMock();

        $lazyListener->expects($this->once())
            ->method('getDefaultListenerFactory')
            ->willReturn($defaultListenerFactory);

        $lazyListener($expectedEvent);
    }

    /**
     * Assert that the defaultFactory is created and lazy loaded listener is executed correctly.
     *
     * @return void
     */
    public function testDefaultFactoryCreatesAndInvokesLazyListenerMock(): void
    {
        $event = new \stdClass();

        $className = LazyListenerMock::class;
        $arguments = [
            $event, // bit of a hack but we need $event in the fake listener to assert something...
            'foo' => 'bar',
            'test' => 123,
        ];

        $lazyListener = new LazyListener($className, $arguments);

        $lazyListener($event);
    }
}
