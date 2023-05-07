<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Listener\LazyListener;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\EventDispatcher\Listener\LazyListener
 */
final class LazyListenerTest extends TestCase
{
    public function testIsCallable(): void
    {
        $listener = new LazyListener(
            static function () {
            }
        );

        $this->assertIsCallable($listener);
    }

    /**
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
     * @throws EventListenerException
     * @throws ExpectationFailedException
     *
     * @dataProvider getLazyListenerWillCreateAndDispatchEventData
     */
    public function testLazyListenerWillCreateAndDispatchEvent(
        mixed $expected,
        mixed $factory,
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
        $factory1 = new class () {
            public function create(): callable
            {
                return static fn () => 'hello123';
            }
        };

        $listener1 = new class () {
            public function doSomething(object $event): string
            {
                return 'test123';
            }
        };

        $factory2 = new class ($listener1) {
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
