<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @covers \Arp\EventDispatcher\EventDispatcher
 * @covers \Arp\EventDispatcher\AbstractEventDispatcher
 */
final class EventDispatcherTest extends TestCase
{
    private AddableListenerProviderInterface&MockObject $listenerProvider;

    public function setUp(): void
    {
        $this->listenerProvider = $this->getMockForAbstractClass(AddableListenerProviderInterface::class);
    }

    public function testImplementsEventDispatcherInterface(): void
    {
        $this->assertInstanceOf(EventDispatcherInterface::class, new EventDispatcher($this->listenerProvider));
    }

    public function testImplementsAddListenerAwareInterface(): void
    {
        $this->assertInstanceOf(AddListenerAwareInterface::class, new EventDispatcher($this->listenerProvider));
    }

    public function testDispatchWillPreventEventPropagationIfProvidedEventHasPropagationStopped(): void
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        /** @var StoppableEventInterface&MockObject $event */
        $event = $this->getMockForAbstractClass(StoppableEventInterface::class);

        $event->expects($this->once())
            ->method('isPropagationStopped')
            ->willReturn(true);

        $this->listenerProvider->expects($this->never())
            ->method('getListenersForEvent');

        $this->assertSame($event, $eventDispatcher->dispatch($event));
    }

    /**
     * @dataProvider getDispatchWillPreventEventPropagationIfItIsStoppedWithinAListenerData
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

        /** @var StoppableEventInterface&MockObject $event */
        $event = $this->getMockForAbstractClass(StoppableEventInterface::class);

        $eventListeners = [];
        $isStopped = [];

        for ($x = 0; $x < $listenerCount; $x++) {
            $eventListeners[] = static function () {
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
     * @return array<mixed>
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
     * @dataProvider getDispatchWillInvokeEventListenersForProvidedEventData
     */
    public function testDispatchWillInvokeEventListenersForProvidedEvent(
        object $event,
        int $numberOfListeners = 0
    ): void {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        $listeners = [];

        for ($x = 0; $x < $numberOfListeners; $x++) {
            $listeners[] = static function ($event) {
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
     * @return array<mixed>
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
