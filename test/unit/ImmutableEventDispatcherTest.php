<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\ImmutableEventDispatcher;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @covers \Arp\EventDispatcher\ImmutableEventDispatcher
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher
 */
final class ImmutableEventDispatcherTest extends TestCase
{
    /**
     * @var ListenerProviderInterface&MockObject
     */
    private $listenerProvider;

    /**
     * Prepare the test case dependencies.
     */
    public function setUp(): void
    {
        $this->listenerProvider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
    }

    /**
     * Assert that the event dispatcher is an instance of EventDispatcherInterface.
     */
    public function testImplementsEventDispatcherInterface(): void
    {
        $eventDispatcher = new ImmutableEventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventDispatcher);
    }

    /**
     * Assert that the provided Event dispatcher's events will be dispatched when calling dispatch()
     */
    public function testDispatchOfInternalEventDispatcher(): void
    {
        $eventDispatcher = new ImmutableEventDispatcher($this->listenerProvider);

        $this->listenerProvider->expects($this->once())
            ->method('getListenersForEvent')
            ->willReturn([]);

        $eventDispatcher->dispatch(new \stdClass());
    }
}
