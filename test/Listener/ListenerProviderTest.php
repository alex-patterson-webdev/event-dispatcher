<?php

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\ListenerCollectionInterface;
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

    /**
     * Assert that the listener provider will return a clone of the internal listener collection which contains
     * the required event listeners in the correct priority order.
     *
     * @param iterable|callable[]  $listeners  The collection of event listeners to test.
     *
     * @dataProvider getAddListenersForEventAndGetListenerForEventData
     * @test
     */
    public function testAddListenersForEventAndGetListenerForEvent(iterable $listeners) : void
    {
        $provider = new ListenerProvider($this->eventNameResolver);

        $event = new \stdClass;

        $provider->addListenersForEvent($event, $listeners);

        $collection = $provider->getListenersForEvent($event);

        $this->assertInstanceOf(ListenerCollectionInterface::class, $collection);

        $listeners = ($listeners instanceof \Traversable)
            ? iterator_to_array($listeners)
            : $listeners;

        foreach($collection as $index => $item) {
            $this->assertSame($listeners[$index], $item);
        }
    }

    /**
     * @return array
     */
    public function getAddListenersForEventAndGetListenerForEventData() : array
    {
        $collectionIterator = new \ArrayObject([
            static function ($event) {},
            static function ($event) {},
            static function ($event) {},
        ]);

        $collection = $this->getMockForAbstractClass(ListenerCollectionInterface::class);
        $collection->expects($this->exactly(2))
            ->method('getIterator')
            ->willReturn($collectionIterator);

        return [

            // Empty listener collection test...
            [
                [],
            ],

            // One Listener (with type hint)
            [
                [
                    static function(\stdClass $event) {
                        return 'Foo';
                    }
                ]
            ],

            // Three Listeners
            [
                [
                    static function(\stdClass $event) {
                        return 'Foo';
                    },
                    static function(\stdClass $event) {
                        return 'Bar';
                    },
                    static function(\stdClass $event) {
                        return 'Baz';
                    }
                ],
            ],

            // Traversable test
            [
                $collection,
            ]
        ];
    }

}
