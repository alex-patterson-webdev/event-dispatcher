<?php

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
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
     * Prepare the test case dependencies.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->listenerProvider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
    }

    /**
     * Ensure that the EventDispatcher implements EventDispatcherInterface.
     *
     * @test
     */
    public function testImplementsEventDispatcherInterface() : void
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventDispatcher);
    }
}
