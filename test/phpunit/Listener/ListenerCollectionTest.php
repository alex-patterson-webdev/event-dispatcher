<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\ListenerCollection;
use Arp\EventDispatcher\Listener\ListenerCollectionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener
 */
final class ListenerCollectionTest extends TestCase
{
    /**
     * Assert that the listener collection implements ListenerCollectionInterface.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection
     */
    public function testImplementsListenerCollectionInterface(): void
    {
        $collection = new ListenerCollection();

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);
    }

    /**
     * Assert that getIterator() will return a clone of the listener priority queue.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection::getIterator
     */
    public function testGetIteratorWillReturnCloneOfListenerQueue(): void
    {
        $collection = new ListenerCollection();

        $listeners = [
            'Foo' => static function () {
                return 'Foo';
            },
            'Bar' => static function () {
                return 'Bar';
            },
        ];

        $collection->addListeners($listeners, 1);

        $results = [];
        foreach ($collection as $index => $listener) {
            $results[] = $listener();
        }

        $this->assertSame(['Foo', 'Bar'], $results);
    }

    /**
     * Assert that the count() method will return an integer matching the number of listeners added to the collection.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection::addListeners
     */
    public function testCountWillReturnIntegerMatchingTheNumberOfEventListeners(): void
    {
        $collection = new ListenerCollection();

        /** @var callable[] $listeners */
        $listeners = [
            static function () {
            },
            static function () {
            },
            static function () {
            },
            static function () {
            },
        ];

        $collection->addListeners($listeners);

        $this->assertSame(count($listeners), $collection->count());
    }

    /**
     * Assert that we can add a collection of event listeners via the __construct.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection
     */
    public function testEventListenersCanBeAddedViaConstructor(): void
    {
        $expected = [
            'foo',
            'bar',
            'baz',
        ];

        $listeners = [
            static function () {
                return 'foo';
            },
            static function () {
                return 'bar';
            },
            static function () {
                return 'baz';
            },
        ];

        $collection = new ListenerCollection($listeners);

        $results = [];
        foreach ($collection as $index => $listener) {
            $results[] = $listener();
        }

        $this->assertSame($expected, $results);
        $this->assertSame(count($listeners), $collection->count());
    }

    /**
     * Assert that the listeners priorities are respected, regardless of when the listener is registered with the
     * collection.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection::addListener
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection::addListeners
     */
    public function testListenerPriorities(): void
    {
        $listeners = [
            static function () {
                return 5;
            },
            static function () {
                return 1;
            },
            static function () {
                return 3;
            },
            static function () {
                return 7;
            },
            static function () {
                return 4;
            },
            static function () {
                return 8;
            },
            static function () {
                return 6;
            },
            static function () {
                return 2;
            },
        ];

        $collection = new ListenerCollection();

        $collection->addListener($listeners[0], 300); // 5
        $collection->addListener($listeners[1], 700); // 1
        $collection->addListener($listeners[2], 500); // 3
        $collection->addListener($listeners[3], 101); // 7
        $collection->addListener($listeners[4], 400); // 4
        $collection->addListener($listeners[5], 100); // 8
        $collection->addListener($listeners[6], 200); // 6
        $collection->addListener($listeners[7], 600); // 2

        $results = [];
        foreach ($collection as $item) {
            $results[] = $item();
        }

        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8], $results);
    }

    /**
     * Assert that the listeners natural order is respected when provided with event listeners with the same
     * priorities. This means that the collection operates on a first in first out basis.
     *
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection::addListener
     * @covers \Arp\EventDispatcher\Listener\ListenerCollection::addListeners
     */
    public function testListenerPrioritiesRespectNaturalOrderWhenPrioritiesAreTheSame(): void
    {
        $listeners = [
            static function () {
                return 5;
            },
            static function () {
                return 1;
            },
            static function () {
                return 3;
            },
            static function () {
                return 7;
            },
            static function () {
                return 4;
            },
            static function () {
                return 8;
            },
            static function () {
                return 6;
            },
            static function () {
                return 2;
            },
        ];

        $collection = new ListenerCollection();

        $collection->addListener($listeners[0], 1); // 5
        $collection->addListener($listeners[1], 1); // 1
        $collection->addListener($listeners[2], 1); // 3
        $collection->addListener($listeners[3], 1); // 7
        $collection->addListener($listeners[4], 1); // 4
        $collection->addListener($listeners[5], 1); // 8
        $collection->addListener($listeners[6], 1); // 6
        $collection->addListener($listeners[7], 1); // 2

        $results = [];
        foreach ($collection as $item) {
            $results[] = $item();
        }

        $this->assertSame([5, 1, 3, 7, 4, 8, 6, 2], $results);
    }
}
