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
     * @test
     */
    public function testImplementsListenerCollectionInterface(): void
    {
        $collection = new ListenerCollection();

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);
    }

    /**
     * Assert that
     *
     * @test
     */
    public function testGetIteratorWillReturnCloneOfListenerQueue(): void
    {
        $collection = new ListenerCollection();

        $listeners = [
            static function () {
                return '0';
            },
            static function () {
                return '1';
            },
            static function () {
                return '2';
            },
            static function () {
                return '3';
            },
            static function () {
                return '4';
            },
            static function () {
                return '5';
            },
        ];

        foreach ($listeners as $index => $listener) {
            $collection->addListener($listener, 10);
        }

        $cloneOfQueue = $collection->getIterator();

        foreach ($cloneOfQueue as $index => $item) {
            $expected = $listeners[$index]();
            $value = $item();

            $this->assertSame(
                $expected,
                $value, sprintf('Index \'%d\' is invalid', $index)
            );
        }
    }

    /**
     * Assert that the count() method will return an integer matching the number of listeners added to the collection.
     *
     * @test
     */
    public function testCountWillReturnIntegerMatchingTheNumberOfEventListeners(): void
    {
        $collection = new ListenerCollection;

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
     * @test
     */
    public function testEventListenersCanBeAddedViaConstructor(): void
    {
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

        foreach ($collection->getIterator() as $index => $listener) {
            $this->assertSame($listeners[$index], $listener);
        }

        $this->assertSame(count($listeners), $collection->count());
    }
}
