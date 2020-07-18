<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Listener\ListenerCollection;
use Arp\EventDispatcher\Listener\ListenerCollectionInterface;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener
 */
final class ListenerProviderTest extends TestCase
{
    /**
     * @var EventNameResolverInterface|MockObject
     */
    private $eventNameResolver;

    /**
     * Prepare the test case dependencies.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->eventNameResolver = $this->getMockForAbstractClass(EventNameResolverInterface::class);
    }

    /**
     * Assert that the listener provider implements ListenerProviderInterface.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerProvider
     */
    public function testImplementsListenerProviderInterface(): void
    {
        $provider = new ListenerProvider();

        $this->assertInstanceOf(ListenerProviderInterface::class, $provider);
    }

    /**
     * Assert that the listener collection implements AddListenerAwareInterface.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerProvider
     */
    public function testImplementsAddListenerAwareInterface(): void
    {
        $provider = new ListenerProvider();

        $this->assertInstanceOf(AddListenerAwareInterface::class, $provider);
    }


    /**
     * Assert that the listener provider will return a clone of the internal listener collection which contains
     * the required event listeners in the correct priority order.
     *
     * @param iterable|callable[] $listeners The collection of event listeners to test.
     *
     * @dataProvider getAddListenersForEventAndGetListenerForEventData
     */
    public function testAddListenersForEventAndGetListenerForEvent(iterable $listeners): void
    {
        $provider = new ListenerProvider($this->eventNameResolver);

        $this->eventNameResolver
            ->method('resolveEventName')
            ->willReturn(\stdClass::class);

        $event = new \stdClass();

        $provider->addListenersForEvent($event, $listeners);

        $collection = $provider->getListenersForEvent($event);

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);

        $expected = [];
        foreach ($listeners as $listener) {
            $expected[] = $listener($event);
        }

        $actual = [];
        foreach ($collection as $listener) {
            $actual[] = $listener($event);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public function getAddListenersForEventAndGetListenerForEventData(): array
    {
        return [

            // Empty listener collection test...
            [
                [],
            ],

            // One Listener (with type hint)
            [
                [
                    static function () {
                        return 1;
                    },
                ],
            ],

            // Three Listeners
            [
                [
                    static function () {
                        return 0;
                    },
                    static function () {
                        return 1;
                    },
                    static function () {
                        return 2;
                    },
                ],
            ],

            // Traversable test
            [
                new ListenerCollection([
                    static function () {
                        return 0;
                    },
                    static function () {
                        return 1;
                    },
                    static function () {
                        return 2;
                    },
                ]),
            ],
        ];
    }

    /**
     * Assert that a EventListenerException will be thrown if the provided event name cannot be resolved.
     *
     * @return void
     */
    public function testGetListenersForEventWillThrowEventListenerExceptionIfTheEventNameCannotBeResolved(): void
    {
        $provider = new ListenerProvider($this->eventNameResolver);

        $event = new \stdClass();

        $exceptionMessage = 'Test exception message';
        $exception = new EventNameResolverException($exceptionMessage);

        $this->eventNameResolver->expects($this->once())
            ->method('resolveEventName')
            ->with($event)
            ->willThrowException($exception);

        $this->expectException(EventListenerException::class);
        $this->expectExceptionMessage(sprintf('Failed to resolve the event name : %s', $exceptionMessage));

        $provider->getListenersForEvent($event);
    }

    /**
     * Assert calls to addListenerForEvent() will fetch a listener collection and add the provided listener.
     *
     * @param callable $listener
     * @param int      $priority
     *
     * @dataProvider getAddListenerForEventData
     * @return void
     */
    public function testAddListenerForEvent(callable $listener, int $priority = 1): void
    {
        /** @var ListenerProvider|MockObject $provider */
        $provider = $this->getMockBuilder(ListenerProvider::class)
            ->setConstructorArgs([$this->eventNameResolver])
            ->onlyMethods(['createListenerCollection'])
            ->getMock();

        $event = new \stdClass();
        $eventName = \stdClass::class;

        $this->eventNameResolver->expects($this->once())
            ->method('resolveEventName')
            ->with($event)
            ->willReturn($eventName);

        /** @var ListenerCollectionInterface|MockObject $collection */
        $collection = $this->getMockForAbstractClass(ListenerCollectionInterface::class);

        $provider->expects($this->once())
            ->method('createListenerCollection')
            ->willReturn($collection);

        $collection->expects($this->once())
            ->method('addListener')
            ->with($listener, $priority);

        $provider->addListenerForEvent($event, $listener, $priority);
    }

    /**
     * @return array
     */
    public function getAddListenerForEventData(): array
    {
        return [
            [
                static function () {
                },
            ],
            [
                static function () {
                },
                100,
            ],
            [
                static function () {
                },
                -100,
            ],
        ];
    }
}
