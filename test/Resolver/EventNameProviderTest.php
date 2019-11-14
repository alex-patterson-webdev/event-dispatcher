<?php

namespace ArpTest\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use PHPUnit\Framework\TestCase;

/**
 * EventNameProviderTest
 *
 * @package ArpTest\EventDispatcher\Resolver
 */
class EventNameProviderTest extends TestCase
{
    /**
     * Assert that the EventNameResolver implements EventNameResolverInterface
     *
     * @test
     */
    public function testImplementsEventNameResolverInterface() : void
    {
        $resolver = new EventNameResolver();

        $this->assertInstanceOf(EventNameResolverInterface::class, $resolver);
    }
}
