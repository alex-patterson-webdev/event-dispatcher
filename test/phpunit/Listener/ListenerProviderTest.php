<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
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
     * @return void
     */
    public function testImplementsListenerProviderInterface(): void
    {
        $provider = new ListenerProvider();

        $this->assertInstanceOf(ListenerProviderInterface::class, $provider);
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

        $event = new \stdClass();

        $provider->addListenersForEvent($event, $listeners);

        $collection = $provider->getListenersForEvent($event);

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);

        $listeners = ($listeners instanceof \Traversable)
            ? iterator_to_array($listeners)
            : $listeners;

        foreach ($collection as $index => $item) {
            $this->assertSame($listeners[$index], $item);
        }
    }

    /**
     * @return array
     */
    public function getAddListenersForEventAndGetListenerForEventData(): array
    {
        $collectionIterator = new \ArrayObject([
            static function ($event) {
            },
            static function ($event) {
            },
            static function ($event) {
            },
        ]);

        $collection = $this->getMockForAbstractClass(ListenerCollectionInterface::class);
        $collection->expects($this->exactly(2))
            ->method('getIterator')
            ->willReturn($collectionIterator);

        return [

            // Empty listener collection test...
            [
                [],
            ],

            // One Listener (with type hint)
            [
                [
                    static function (\stdClass $event) {
                        return $event->foo ?? 'Bar';
                    },
                ],
            ],

            // Three Listeners
            [
                [
                    static function (\stdClass $event) {
                        return $event->foo ?? 'Bar';
                    },
                    static function (\stdClass $event) {
                        return $event->foo ?? 'Bar';
                    },
                    static function (\stdClass $event) {
                        return $event->foo ?? 'Bar';
                    },
                ],
            ],

            // Traversable test
            [
                $collection,
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

        $event = new \stdClass;

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
