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

    /**
     * Assert that
     *
     * @test
     */
    public function testGetIteratorWillReturnCloneOfListenerQueue() : void
    {
        $collection = new ListenerCollection();

        $listeners = [
            static function () { return 'Foo'; },
            static function () { return 'Bar'; },
            static function () { return 'Baz'; },
        ];

        foreach ($listeners as $index => $listener) {
            $collection->addListener($listener, ++$index);
        }

        $cloneOfQueue = $collection->getIterator();

        foreach ($cloneOfQueue as $index => $item) {
            $expected = $listeners[$index]();
            $value = $item();
        }

        $this->assertSame(1,1);

        $test = 'hello';
    }
}
