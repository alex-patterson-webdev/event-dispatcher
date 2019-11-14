<?php

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\ListenerCollection;
use Arp\EventDispatcher\Listener\ListenerCollectionInterface;
use PHPUnit\Framework\TestCase;

/**
 * ListenerCollectionTest
 *
 * @package ArpTest\EventDispatcher\Listener
 */
class ListenerCollectionTest extends TestCase
{
    /**
     * Assert that the listener collection implements ListenerCollectionInterface.
     *
     * @test
     */
    public function testImplementsListenerCollectionInterface() : void
    {
        $collection = new ListenerCollection();

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);
    }
}
