<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Event;

use Arp\EventDispatcher\Event\Parameters;
use Arp\EventDispatcher\Event\ParametersInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Event
 */
final class ParametersTest extends TestCase
{
    /**
     * Assert that the class implements the ParametersInterface.
     *
     * @test
     */
    public function testImplementsParametersInterface(): void
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
    public function testGetSetParameters(array $data): void
    {
        $params = new Parameters();

        $this->assertEmpty($params->getParams());

        $params->setParams($data);

        $this->assertEquals($data, $params->getParams());
    }

    /**
     * @return array
     */
    public function getGetSetParametersData(): array
    {
        return [
            // Simple
            [
                [
                    'foo' => 'bar',
                ],
            ],

            // Extra data types
            [
                [
                    'test' => 'Red',
                    'blue' => true,
                    'Hello' => 455.667,
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
                        '789' => 123.456,
                    ],
                    'object' => new \stdClass(),
                ],
            ],
        ];
    }
}
