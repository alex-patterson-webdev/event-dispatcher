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
     * @covers \Arp\EventDispatcher\Event\Parameters
     */
    public function testImplementsParametersInterface(): void
    {
        $params = new Parameters([]);

        $this->assertInstanceOf(ParametersInterface::class, $params);
    }

    /**
     * Assert that the class implements \ArrayAccess
     *
     * @covers \Arp\EventDispatcher\Event\Parameters
     */
    public function testImplementsArrayAccess(): void
    {
        $params = new Parameters([]);

        $this->assertInstanceOf(\ArrayAccess::class, $params);
    }

    /**
     * Assert that parameters can be set and fetched vis getParams() and setParams().
     *
     * @param array $data The test case data.
     *
     * @covers \Arp\EventDispatcher\Event\Parameters::getParams
     * @covers \Arp\EventDispatcher\Event\Parameters::setParams
     *
     * @dataProvider getGetSetParametersData
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
                    'callable' => static function () {
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

    /**
     * Assert that isEmpty() will return true when the params collection is empty.
     *
     * @covers \Arp\EventDispatcher\Event\Parameters::isEmpty
     */
    public function testIsEmptyReturnsTrueWhenEmpty(): void
    {
        $params = new Parameters([]);

        $this->assertTrue(
            $params->isEmpty(),
            'Method isEmpty() returned false when the params collection was empty'
        );
    }

    /**
     * Assert that getParams() will return true when the params collection is not empty.
     *
     * @covers \Arp\EventDispatcher\Event\Parameters::isEmpty
     */
    public function testIsEmptyReturnsFalseWhenNotEmpty(): void
    {
        $params = new Parameters([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        $this->assertFalse(
            $params->isEmpty(),
            'Method isEmpty() returned true when the params collection was not empty'
        );
    }

    /**
     * Asser that the count method returns the correct count of the number of params within the collection.
     *
     * @param array $data  The data test set
     *
     * @dataProvider getCountData
     *
     * @covers \Arp\EventDispatcher\Event\Parameters::count
     */
    public function testCount(array $data): void
    {
        $params = new Parameters($data);

        $this->assertSame(count($data), $params->count());
    }

    /**
     * @return array
     */
    public function getCountData(): array
    {
        return [
            [
                [],
            ],

            [
                ['foo' => 'bar'],
            ],

            [
                [
                    'foo' => 'bar',
                    'baz' => 'test',
                ],
            ],
        ];
    }

    /**
     * Assert that getValues() will return a numerically indexed array of the parameter values.
     *
     * @param array $params The parameters that should be set.
     *
     * @covers \Arp\EventDispatcher\Event\Parameters::getValues
     *
     * @dataProvider getGetValuesWillReturnTheParametersValuesData
     */
    public function testGetValuesWillReturnTheParametersValues(array $params): void
    {
        $parameters = new Parameters($params);

        $this->assertSame(array_values($params), $parameters->getValues());
    }

    /**
     * @return array
     */
    public function getGetValuesWillReturnTheParametersValuesData(): array
    {
        return [
            [
                [
                    'foo' => 'bar',
                    'baz' => 'boo',
                    'hello' => 'test',
                ]
            ],
        ];
    }

    /**
     * Assert that a parameter has been with calls to hasParam().
     *
     * @covers \Arp\EventDispatcher\Event\Parameters::hasParam
     */
    public function testHasParamWillReturnBool(): void
    {
        $data = [
            'foo' => 123,
            'bar' => 'baz',
            'bax' => 45,
        ];
        $parameters = new Parameters($data);

        $this->assertTrue($parameters->hasParam('foo'));
        $this->assertTrue($parameters->hasParam('bar'));
        $this->assertTrue($parameters->hasParam('bax'));

        $this->assertFalse($parameters->hasParam('test'));
        $this->assertFalse($parameters->hasParam('fred'));
        $this->assertFalse($parameters->hasParam('bob'));
    }
}
