<?php

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * ListenerProviderTest
 *
 * @package ArpTest\EventDispatcher\Listener
 */
class ListenerProviderTest extends TestCase
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
     * @test
     */
    public function testImplementsListenerProviderInterface() : void
    {
        $provider = new ListenerProvider($this->eventNameResolver);

        $this->assertInstanceOf(ListenerProviderInterface::class, $provider);
    }
}
