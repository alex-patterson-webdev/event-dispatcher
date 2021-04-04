<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Listener\LazyListener;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @covers  \Arp\EventDispatcher\Listener\LazyListener
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener
 */
final class LazyListenerTest extends TestCase
{
    /**
     * testIsCallable
     */
    public function testIsCallable(): void
    {
        $listener = new LazyListener(
            static function () {
            }
        );

        $this->assertIsCallable($listener);
    }

    /**
     * Assert a EventListenerException is thrown from __construct if the provided $factory is invalid
     *
     * @param mixed $factory
     *
     * @dataProvider getConstructWillThrowEventListenerExceptionIfTheConfiguredFactoryIsNotCallableData
     */
    public function testConstructWillThrowEventListenerExceptionIfTheConfiguredFactoryIsNotCallable($factory): void
    {
        $this->expectException(EventListenerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The event listener factory must be of type \'callable\' or \'object\'; \'%s\' provided in \'%s\'',
                is_object($factory) ? get_class($factory) : gettype($factory),
                LazyListener::class
            )
        );

        new LazyListener($factory);
    }

    /**
     * @return array<mixed>
     */
    public function getConstructWillThrowEventListenerExceptionIfTheConfiguredFactoryIsNotCallableData(): array
    {
        return [
            ['hello'],
            [true],
            [123],
        ];
    }

    /**
     * Assert that non-callable factory methods will raise a EventListenerException in __invoke()
     *
     * @throws EventListenerException
     */
    public function testInvokeWillThrowEventListenerExceptionIfTheFactoryMethodIsNotCallable(): void
    {
        $event = new \stdClass();
        $factory = new \stdClass();

        $lazyListener = new LazyListener($factory);

        $this->expectException(EventListenerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The method \'%s\' is not callable for lazy load factory \'%s\'',
                '__invoke',
                'array'
            )
        );

        $lazyListener($event);
    }

    /**
     * Assert that a non-callable listener method will raise a EventListenerException in __invoke()
     *
     * @throws EventListenerException
     */
    public function testInvokeWillThrowEventListenerExceptionIfTheListenerMethodIsNotCallable(): void
    {
        $event = new \stdClass();
        $listener = new \stdClass();
        $factory = static fn () => $listener;

        $lazyListener = new LazyListener($factory);

        $this->expectException(EventListenerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The method \'%s\' is not callable for lazy load event listener \'%s\'',
                '__invoke',
                'array'
            )
        );

        $lazyListener($event);
    }

    /**
     * @param mixed       $expected
     * @param mixed       $factory
     * @param string|null $factoryMethod
     * @param string|null $listenerMethod
     *
     * @throws EventListenerException
     * @throws ExpectationFailedException
     *
     * @dataProvider getLazyListenerWillCreateAndDispatchEventData
     */
    public function testLazyListenerWillCreateAndDispatchEvent(
        $expected,
        $factory,
        ?string $factoryMethod = null,
        ?string $listenerMethod = null
    ): void {
        $event = new \stdClass();
        $lazyListener = new LazyListener($factory, $factoryMethod, $listenerMethod);

        $this->assertSame($expected, $lazyListener($event));
    }

    /**
     * @return array<mixed>
     */
    public function getLazyListenerWillCreateAndDispatchEventData(): array
    {
        $factory1 = new class() {
            public function create(): callable
            {
                return static fn () => 'hello123';
            }
        };

        $listener1 = new class() {
            public function doSomething(object $event): string
            {
                return 'test123';
            }
        };

        $factory2 = new class($listener1) {
            private object $listener;

            public function __construct(object $listener)
            {
                $this->listener = $listener;
            }

            public function create(): object
            {
                return $this->listener;
            }
        };

        return [
            [
                'hello123',
                static fn () => static fn () => 'hello123',
            ],

            [
                'hello123',
                $factory1,
                'create'
            ],

            [
                'test123',
                $factory2,
                'create',
                'doSomething',
            ]
        ];
    }
}
