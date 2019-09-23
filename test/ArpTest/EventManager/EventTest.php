<?php

namespace ArpTest\EventManager;

use Arp\EventManager\Event;
use Arp\EventManager\EventInterface;
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

}