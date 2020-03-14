<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher
 */
final class EventDispatcherTest extends TestCase
{
    /**
     * @var ListenerProviderInterface|MockObject
     */
    private $listenerProvider;

    /**
     * Prepare the test dependencies.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->listenerProvider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
    }

    /**
     * Ensure that the event manager implements EventDispatcherInterface.
     *
     * @test
     */
    public function testImplementsEventDispatcherInterface(): void
    {
        $eventManager = new EventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventManager);
    }

    /**
     * If we call dispatch with a StoppableEventInterface that already has propagation stopped, no event listeners
     * should be triggered.
     *
     * @test
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
     * @test
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
     * @test
     */
    public function testDispatchWillInvokeEventListenersForProvidedEvent($event, $numberOfListeners = 0): void
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        $listeners = [];

        for ($x = 0; $x < $numberOfListeners; $x++) {
            $listeners[] = static function ($event) use ($x) {
                return 'Foo' . $x;
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
                new \stdClass,
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

}
