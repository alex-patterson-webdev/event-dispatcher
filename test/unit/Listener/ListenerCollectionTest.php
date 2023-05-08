<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\ListenerCollection;
use Arp\EventDispatcher\Listener\ListenerCollectionInterface;
use PHPUnit\Framework\TestCase;

final class ListenerCollectionTest extends TestCase
{
    public function testImplementsListenerCollectionInterface(): void
    {
        $collection = new ListenerCollection();

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);
    }

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

        $collection->addListeners($listeners);

        $results = [];
        foreach ($collection as $listener) {
            $results[] = $listener();
        }

        $this->assertSame(['Foo', 'Bar'], $results);
    }

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
        foreach ($collection as $listener) {
            $results[] = $listener();
        }

        $this->assertSame($expected, $results);
        $this->assertSame(count($listeners), $collection->count());
    }

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

        $collection->addListener($listeners[0]); // 5
        $collection->addListener($listeners[1]); // 1
        $collection->addListener($listeners[2]); // 3
        $collection->addListener($listeners[3]); // 7
        $collection->addListener($listeners[4]); // 4
        $collection->addListener($listeners[5]); // 8
        $collection->addListener($listeners[6]); // 6
        $collection->addListener($listeners[7]); // 2

        $results = [];
        foreach ($collection as $item) {
            $results[] = $item();
        }

        $this->assertSame([5, 1, 3, 7, 4, 8, 6, 2], $results);
    }
}
