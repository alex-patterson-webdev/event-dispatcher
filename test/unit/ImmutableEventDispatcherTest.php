<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher;

use Arp\EventDispatcher\ImmutableEventDispatcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @covers \Arp\EventDispatcher\ImmutableEventDispatcher
 */
final class ImmutableEventDispatcherTest extends TestCase
{
    private ListenerProviderInterface&MockObject $listenerProvider;

    public function setUp(): void
    {
        $this->listenerProvider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
    }

    public function testImplementsEventDispatcherInterface(): void
    {
        $eventDispatcher = new ImmutableEventDispatcher($this->listenerProvider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventDispatcher);
    }

    public function testDispatchOfInternalEventDispatcher(): void
    {
        $eventDispatcher = new ImmutableEventDispatcher($this->listenerProvider);

        $this->listenerProvider->expects($this->once())
            ->method('getListenersForEvent')
            ->willReturn([]);

        $eventDispatcher->dispatch(new \stdClass());
    }
}
