<?php

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Exception\InvalidArgumentException;
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
            static function () { return '0'; },
            static function () { return '1'; },
            static function () { return '2'; },
            static function () { return '3'; },
            static function () { return '4'; },
            static function () { return '5'; },
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
     * Assert that an InvalidArgumentException is thrown if the $listeners provided to addListeners() are
     * of an invalid type.
     *
     * @test
     */
    public function testAddListenerWillThrowInvalidArgumentExceptionWhenPassedInvalidListeners() : void
    {
        $collection = new ListenerCollection();

        $listeners = \stdClass::class;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'The \'listeners\' argument must be an \'array\' or object of type \'%s\'; \'%s\' provided in \'%s::%s\'.',
            \Traversable::class,
            gettype($listeners),
            ListenerCollection::class,
            'addListeners'
        ));

        $collection->addListeners($listeners);
    }

    /**
     * Assert that the count() method will return an integer matching the number of listeners added to the collection.
     *
     * @test
     */
    public function testCountWillReturnIntegerMatchingTheNumberOfEventListeners() : void
    {
        $collection = new ListenerCollection;

        /** @var callable[] $listeners */
        $listeners = [
            static function () {},
            static function () {},
            static function () {},
            static function () {},
        ];

        $collection->addListeners($listeners);

        $this->assertSame(count($listeners), $collection->count());
    }
}
