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
}
