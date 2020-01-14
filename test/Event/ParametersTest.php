<?php


namespace ArpTest\EventDispatcher\Event;

use Arp\EventDispatcher\Event\Parameters;
use Arp\EventDispatcher\Event\ParametersInterface;
use PHPUnit\Framework\TestCase;

/**
 * ParametersTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Event
 */
class ParametersTest extends TestCase
{
    /**
     * Assert that the class implements the ParametersInterface.
     *
     * @test
     */
    public function testImplementsParametersInterface()
    {
        $params = new Parameters([]);

        $this->assertInstanceOf(ParametersInterface::class, $params);
    }

    /**
     * @param array $data
     *
     * @dataProvider getGetSetParametersData
     * @test
     */
    public function testGetSetParameters(array $data)
    {
        $params = new Parameters();

        $this->assertEmpty($params->getParams());

        $params->setParams($data);

        $this->assertEquals($data, $params->getParams());
    }

    /**
     * @return array
     */
    public function getGetSetParametersData()
    {
        return [
            // Simple
            [
                [
                    'foo' => 'bar',
                ]
            ],

            // Extra data types
            [
                [
                    'test'  => 'Red',
                    'blue'  => true,
                    123     => 455.667,
                ],
            ],

            // Complex data types.
            [
                [
                    'callable' => function ($x) {
                        return 'test';
                    },
                    'data' => [
                        'foo' => 'bar',
                        789 => 123.456,
                    ],
                    'object' => new \stdClass(),
                ]
            ],
        ];
    }
}