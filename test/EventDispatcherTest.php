<?php

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * EventDispatcherTest
 *
 * @package ArpTest\EventDispatcher
 */
class EventDispatcherTest extends TestCase
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
    public function testImplementsEventDispatcherInterface()
    {
        $eventManager = new EventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventManager);
    }

    /**
     * Assert that a StoppableEventInterface event is excluded from event propagation if already set to true.
     *
     * @test
     */
    public function testDispatchWillRespectStopPropagationEventIfTrue()
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
     * Assert that dispatch() will invoke the require event listeners returned from the listener provider.
     *
     * @param object $event
     * @param int    $numberOfListeners
     *
     * @dataProvider getDispatchWillInvokeEventListenersForProvidedEventData
     * @test
     */
    public function testDispatchWillInvokeEventListenersForProvidedEvent($event, $numberOfListeners = 0)
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        $listeners = [];

        for($x = 0; $x < $numberOfListeners; $x++) {
            $listeners[] = function ($event) {

            };
        }

        $this->listenerProvider->expects($this->once())
            ->method('getListenersForEvent')
            ->willReturn($listeners);

        $result = $eventDispatcher->dispatch($event);

        $this->assertTrue(is_object($result));
        $this->assertSame($result, $event);
    }

    /**
     * @return array
     */
    public function getDispatchWillInvokeEventListenersForProvidedEventData()
    {
        return [
            [
                new \stdClass,
                7
            ],

            [
                $this->getMockForAbstractClass(StoppableEventInterface::class)
                    ->expects($this->exactly(5))
                    ->method('isPropagationStopped')
                    ->willReturn(false),
                5
            ]
        ];
    }

}