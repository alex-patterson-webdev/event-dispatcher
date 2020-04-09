<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\EventDispatcher\Listener\ListenerProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher
 */
final class EventDispatcherTest extends TestCase
{
    /**
     * @var ListenerProvider|MockObject
     */
    private $listenerProvider;

    /**
     * Prepare the test dependencies.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->listenerProvider = $this->createMock(ListenerProvider::class);
    }

    /**
     * Ensure that the event manager implements EventDispatcherInterface.
     *
     * @covers \Arp\EventDispatcher\EventDispatcher
     */
    public function testImplementsEventDispatcherInterface(): void
    {
        $eventManager = new EventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventManager);
    }

    /**
     * Ensure that the event manager implements AddListenerAwareInterface.
     *
     * @covers \Arp\EventDispatcher\EventDispatcher
     */
    public function testImplementsAddListenerAwareInterface(): void
    {
        $eventManager = new EventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(AddListenerAwareInterface::class, $eventManager);
    }

    /**
     * If we call dispatch with a StoppableEventInterface that already has propagation stopped, no event listeners
     * should be triggered.
     *
     * @covers \Arp\EventDispatcher\EventDispatcher::dispatch
     * @covers \Arp\EventDispatcher\EventDispatcher::isPropagationStopped
     */
    public function testDispatchWillPreventEventPropagationIfProvidedEventHasPropagationStopped(): void
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        /** @var StoppableEventInterface|MockObject $event */
        $event = $this->getMockForAbstractClass(StoppableEventInterface::class);

        $event->expects($this->once())
            ->method('isPropagationStopped')
            ->willReturn(true);

        $this->listenerProvider->expects($this->never())
            ->method('getListenersForEvent');

        $this->assertSame($event, $eventDispatcher->dispatch($event));
    }

    /**
     * Assert that the event propagation is stopped if we modify the StoppableEventInterface within an event.
     *
     * @param integer $listenerCount The number of event listeners attached to the dispatched event.
     * @param integer $stopIndex     The index that the event listener should stop propagation.
     *
     * @dataProvider getDispatchWillPreventEventPropagationIfItIsStoppedWithinAListenerData
     *
     * @covers \Arp\EventDispatcher\EventDispatcher::dispatch
     * @covers \Arp\EventDispatcher\EventDispatcher::isPropagationStopped
     */
    public function testDispatchWillNotPropagationEventIfItIsStoppedWithinAListener(
        int $listenerCount,
        int $stopIndex
    ): void {
        if ($stopIndex >= $listenerCount) {
            $this->fail(sprintf(
                'The stop index \'%d\' must be less than the number of event listeners \'%d\'.',
                $listenerCount,
                $stopIndex
            ));
        }

        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        /** @var StoppableEventInterface|MockObject $event */
        $event = $this->getMockForAbstractClass(StoppableEventInterface::class);

        $eventListeners = [];
        $isStopped = [];

        for ($x = 0; $x < $listenerCount; $x++) {
            $eventListeners[] = static function (StoppableEventInterface $event) use ($x, $stopIndex) {
            };

            if ($x < ($stopIndex + 1)) {
                $isStopped[] = ($x === $stopIndex);
            }
        }

        $this->listenerProvider->expects($this->once())
            ->method('getListenersForEvent')
            ->willReturn($eventListeners);

        $event->expects($this->exactly(1 + count($isStopped)))
            ->method('isPropagationStopped')
            ->willReturn(false, ...$isStopped);

        $this->assertSame($event, $eventDispatcher->dispatch($event));
    }

    /**
     * @return array
     */
    public function getDispatchWillPreventEventPropagationIfItIsStoppedWithinAListenerData(): array
    {
        return [
            [1, 0],
            [7, 2],
            [23, 20],
            [10, 4],
            [100, 40],
        ];
    }

    /**
     * Assert that dispatch() will invoke the require event listeners returned from the listener provider.
     *
     * @param object $event
     * @param int    $numberOfListeners
     *
     * @dataProvider getDispatchWillInvokeEventListenersForProvidedEventData
     *
     * @covers \Arp\EventDispatcher\EventDispatcher::dispatch
     * @covers \Arp\EventDispatcher\EventDispatcher::isPropagationStopped
     */
    public function testDispatchWillInvokeEventListenersForProvidedEvent($event, $numberOfListeners = 0): void
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        $listeners = [];

        for ($x = 0; $x < $numberOfListeners; $x++) {
            $listeners[] = static function ($event) use ($x) {
                get_class($event);
            };
        }

        $this->listenerProvider->expects($this->once())
            ->method('getListenersForEvent')
            ->willReturn($listeners);

        $result = $eventDispatcher->dispatch($event);

        $this->assertIsObject($result);
        $this->assertSame($result, $event);
    }

    /**
     * @return array
     */
    public function getDispatchWillInvokeEventListenersForProvidedEventData(): array
    {
        return [
            [
                new \stdClass(),
                7,
            ],
            [
                $this->getMockForAbstractClass(StoppableEventInterface::class)
                    ->expects($this->exactly(5))
                    ->method('isPropagationStopped')
                    ->willReturn(false),
                5,
            ],
        ];
    }

    /**
     * Assert that calls to addListenerForEvent() proxies to the internal ListenerProvider.
     *
     * @covers \Arp\EventDispatcher\EventDispatcher::addListenerForEvent
     *
     * @throws EventListenerException
     */
    public function testAddListenerForEventWillProxyToInternalListenerProvider(): void
    {
        $dispatcher = new EventDispatcher($this->listenerProvider);

        $event = new \stdClass();
        $priority = 10;
        $listener = static function (\stdClass $event): void {
            echo $event->name;
        };

        $this->listenerProvider->expects($this->once())
            ->method('addListenerForEvent')
            ->with($event, $listener, $priority);

        $dispatcher->addListenerForEvent($event, $listener, $priority);
    }

    /**
     * Assert that calls to addListenerForEvent() proxies to the internal ListenerProvider.
     *
     * @covers \Arp\EventDispatcher\EventDispatcher::addListenersForEvent
     *
     * @throws EventListenerException
     */
    public function testAddListenersForEventWillProxyToInternalListenerProvider(): void
    {
        $dispatcher = new EventDispatcher($this->listenerProvider);

        $event = new \stdClass();
        $priority = 100;
        $listeners = [
            static function (\stdClass $event): void {
                echo $event->name;
            },
            static function (\stdClass $event): void {
                echo $event->name;
            },
        ];

        $this->listenerProvider->expects($this->once())
            ->method('addListenersForEvent')
            ->with($event, $listeners, $priority);

        $dispatcher->addListenersForEvent($event, $listeners, $priority);
    }
}
