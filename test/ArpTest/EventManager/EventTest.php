<?php

namespace ArpTest\EventManager;

use Arp\EventManager\Event;
use Arp\EventManager\EventInterface;
use Arp\EventManager\EventManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * EventTest
 *
 * @package ArpTest\EventManager
 */
class EventTest extends TestCase
{
    /**
     * testImplementsEventInterface
     *
     * Ensure that the event is an instance of EventInterface.
     *
     * @test
     */
    public function testImplementsEventInterface()
    {
        $event = new Event('foo');

        $this->assertInstanceOf(EventInterface::class, $event);
    }

    /**
     * testGetAndSetNameViaConstructor
     *
     * Ensure that the event name can be set and get.
     *
     * @test
     */
    public function testGetAndSetNameViaConstructor()
    {
        $name = 'foo.event';

        $event = new Event($name);

        $this->assertSame($name, $event->getName());
    }

    /**
     * testGetAndSetName
     *
     * Ensure that the event name can be set and get.
     *
     * @test
     */
    public function testGetAndSetName()
    {
        $name = 'foo.event';

        $event = new Event('bar');

        $event->setName($name);

        $this->assertSame($name, $event->getName());
    }

    /**
     * testHasData
     *
     * Ensure that hasData() returns boolean true/false for data values matching $name.
     *
     * @param boolean $expected  The expected hasData() result.
     * @param string  $name      The name of the data key to match.
     * @param array   $data      The data that should be tested.
     *
     * @dataProvider getHasDataData
     * @test
     */
    public function testHasData($expected, $name, array $data)
    {
        $event = new Event('foo.event', $data);

        $this->assertSame($expected, $event->hasData($name));
    }

    /**
     * getHasDataData
     *
     * @return array
     */
    public function getHasDataData()
    {
        return [

            [
                false,
                'foo',
                []
            ],

            [
                true,
                'foo',
                [
                    'foo' => 123,
                    'bar' => 'hello',
                ]
            ],
        ];
    }

    /**
     * testGetData
     *
     * @param array  $data
     * @param string $name
     * @param mixed  $expected
     *
     * @dataProvider getGetDataData
     * @test
     */
    public function testGetData(array $data, $name, $expected = null)
    {
        $event = new Event('foo.event', $data);

        $this->assertSame($expected, $event->getData($name));
    }

    /**
     * getGetDataData
     *
     * @return array
     */
    public function getGetDataData()
    {
        return [
            [
                [
                    'foo' => 'bar',
                ],
                'foo',
                'bar'
            ],
        ];
    }

    /**
     * testRemoveData
     *
     * Ensure that a data value can be removed from the collection.
     *
     * @param array   $data
     * @param string  $name
     *
     * @dataProvider getRemoveDataData
     * @test
     */
    public function testRemoveData(array $data, $name)
    {
        $event = new Event('foo.event', $data);

        $expected = array_key_exists($name, $data);

        $this->assertSame($expected, $event->removeData($name));
    }

    /**
     * getRemoveDataData
     *
     * @return array
     */
    public function getRemoveDataData()
    {
        return [
            [
                [
                    'foo' => 'bar',
                    'hello' => 'world',
                ],
                'hello'
            ],

            [
                [
                    'foo' => 'bar',
                    'hello' => 'world',
                ],
                'bar'
            ],

        ];
    }

    /**
     * testPropagation
     *
     * Ensure that the propagation methods can be updated.
     *
     * @test
     */
    public function testPropagation()
    {
        $event = new Event('event.foo');

        $this->assertTrue($event->propagate());

        $event->setPropagate(false);
        $this->assertFalse($event->propagate());

        $event->setPropagate(true);
        $this->assertTrue($event->propagate());
    }


}