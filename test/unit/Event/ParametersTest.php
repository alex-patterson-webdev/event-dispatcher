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
     * Assert that the class implements the ParametersInterface
     */
    public function testImplementsParametersInterface(): void
    {
        $params = new Parameters([]);

        $this->assertInstanceOf(ParametersInterface::class, $params);
    }

    /**
     * Assert that the class implements \ArrayAccess
     */
    public function testImplementsArrayAccess(): void
    {
        $params = new Parameters([]);

        $this->assertInstanceOf(\ArrayAccess::class, $params);
    }

    /**
     * Assert that parameters can be set and fetched vis getParams() and setParams()
     *
     * @param array<mixed> $data The test case data
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
     * @return array<mixed>
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
     * Assert that parameter values can be set and get by name
     */
    public function testGetAndSetParam(): void
    {
        $params = new Parameters([]);

        $params->setParam('foo', 123);

        $this->assertSame(123, $params->getParam('foo'));
    }

    /**
     * Assert that the default value is returned for calls to getParam() when the request parameter value cannot be
     * found within the collection
     */
    public function testGetParamWillReturnDefaultValueForNonExistingParam(): void
    {
        $params = new Parameters([]);

        $this->assertNull($params->getParam('bob'));

        $params->setParam('foo', 123);
        $params->setParam('bar', 'Hello');

        $defaultValue = 'default value';

        $this->assertSame($defaultValue, $params->getParam('test', $defaultValue));
    }

    /**
     * Assert that isEmpty() will return true when the params collection is empty
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
     * Assert that getParams() will return true when the params collection is not empty
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
     * Assert that the count method returns the correct count of the number of params within the collection.
     *
     * @param array<mixed> $data  The data test set
     *
     * @dataProvider getCountData
     */
    public function testCount(array $data): void
    {
        $params = new Parameters($data);

        $this->assertSame(count($data), $params->count());
    }

    /**
     * @return array<mixed>
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
     * @param array<mixed> $params The parameters that should be set.
     *
     * @dataProvider getGetValuesWillReturnTheParametersValuesData
     */
    public function testGetValuesWillReturnTheParametersValues(array $params): void
    {
        $parameters = new Parameters($params);

        $this->assertSame(array_values($params), $parameters->getValues());
    }

    /**
     * @return array<mixed>
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
     * Assert that a parameter has been with calls to hasParam()
     */
    public function testHasParamWillReturnBool(): void
    {
        $data = [
            'foo' => 123,
            'bar' => 'baz',
            'bax' => 45,
            'null_test' => null,
        ];
        $parameters = new Parameters($data);

        $this->assertTrue($parameters->hasParam('foo'));
        $this->assertTrue($parameters->hasParam('bar'));
        $this->assertTrue($parameters->hasParam('bax'));
        $this->assertTrue($parameters->hasParam('null_test'));

        $this->assertFalse($parameters->hasParam('test'));
        $this->assertFalse($parameters->hasParam('fred'));
        $this->assertFalse($parameters->hasParam('bob'));
    }

    /**
     * Assert that removeParam() with remove the provided key from the collection. If the parameter name provided
     * exists in the collection, the method should remove it and return boolean true, otherwise just return false
     */
    public function testRemoveParam(): void
    {
        $params = new Parameters([
            'foo' => 'bar',
            'bar' => 'baz',
            'abc' => 123,
        ]);

        $this->assertTrue($params->removeParam('foo'));
        $this->assertFalse($params->removeParam('bob'));
        $this->assertTrue($params->removeParam('abc'));

        $this->assertSame(['bar' => 'baz'], $params->getParams());
    }

    /**
     * Assert that an array of parameters can be removed from the collection
     */
    public function testRemoveParams(): void
    {
        $data = [
            'foo' => 'bar',
            'bar' => 'bsz',
            'test' => 'Hello World',
            'alex' => 'bye!',
        ];

        $params = new Parameters($data);

        $this->assertSame($data, $params->getParams());

        $params->removeParams(['foo', 'bar']);
        unset($data['foo'], $data['bar']);

        $this->assertSame($data, $params->getParams());
    }

    /**
     * Assert that the offsetExists() method will check if the parameter is defined
     */
    public function testOffsetExists(): void
    {
        $params = new Parameters(['test' => 123]);

        $this->assertTrue(isset($params['test']));
        $this->assertFalse(isset($params['hello']));
    }

    /**
     * Assert that the parameters can be set and returned using the array access interface
     */
    public function testOffsetGetAndOffsetSet(): void
    {
        $params = new Parameters([]);

        $params['test'] = 123;
        $params['hello'] = 'Foo';

        $this->assertSame(123, $params['test']);
        $this->assertSame('Foo', $params['hello']);
    }

    /**
     * Assert that a parameter can be removed via the \ArrayAccess api
     */
    public function testOffsetUnset(): void
    {
        $data = [
            'foo' => 'fred',
            'bar' => 'bob',
            'baz' => 'alex',
        ];

        $params = new Parameters($data);

        unset($params['foo'], $data['foo']);

        $this->assertSame($data, $params->getParams());
    }

    /**
     * Assert that the getIterator() method will return an \ArrayIterator instance with a copy of the params collection
     */
    public function testGetIteratorWillReturnArrayIteratorWithParamsCollection(): void
    {
        $data = [
            'foo' => 'fred',
            'bar' => 'bob',
            'baz' => 'alex',
        ];

        $params = new Parameters($data);

        $iterator = $params->getIterator();

        $this->assertSame($data, $iterator->getArrayCopy());
    }
}
