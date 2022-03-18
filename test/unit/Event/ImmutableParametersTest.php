<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Event;

use Arp\EventDispatcher\Event\ImmutableParameters;
use Arp\EventDispatcher\Event\Parameters;
use Arp\EventDispatcher\Event\ParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\EventDispatcher\Event\ImmutableParameters
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Event
 */
final class ImmutableParametersTest extends TestCase
{
    /**
     * @var ParametersInterface&MockObject
     */
    private $parameters;

    /**
     * Prepare the test case dependencies
     */
    public function setUp(): void
    {
        $this->parameters = $this->createMock(ParametersInterface::class);
    }

    /**
     * Assert that the class implements the ParametersInterface
     */
    public function testImplementsParametersInterface(): void
    {
        $params = new ImmutableParameters($this->parameters);

        $this->assertInstanceOf(ParametersInterface::class, $params);
    }

    /**
     * Assert that the class implements \ArrayAccess
     */
    public function testImplementsArrayAccess(): void
    {
        $params = new ImmutableParameters($this->parameters);

        $this->assertInstanceOf(\ArrayAccess::class, $params);
    }

    /**
     * Assert that parameters can be fetched via getParams() but attempts to setParams() will be ignored
     */
    public function testGetParameters(): void
    {
        $data = [
            'hello' => 123,
        ];

        $this->parameters->expects($this->exactly(2))
            ->method('getParams')
            ->willReturnOnConsecutiveCalls([], $data);

        $this->parameters->expects($this->never())->method('setParams');

        $params = new ImmutableParameters($this->parameters);

        $this->assertEmpty($params->getParams());

        // Ignore updates to set params with new data
        $params->setParams(
            [
                'foo' => 'bar',
            ]
        );

        $this->assertEquals($data, $params->getParams());
    }

    /**
     * Assert that calls to count() will proxy to the internal parameters collection
     */
    public function testCountWillReturnParameterCount(): void
    {
        $parameters = new ImmutableParameters($this->parameters);

        $this->parameters->expects($this->once())
            ->method('count')
            ->willReturn(5);

        $this->assertSame(5, $parameters->count());
    }

    /**
     * Assert that calls to isEmpty() will proxy to the internal parameters collection
     */
    public function testIsEmptyWillProxyToInternalParametersCollection(): void
    {
        $immutableParameters = new ImmutableParameters(new Parameters([]));

        $this->assertTrue($immutableParameters->isEmpty());

        $immutableParameters = new ImmutableParameters(
            new Parameters(
                [
                    'foo' => 123,
                ]
            )
        );

        $this->assertFalse($immutableParameters->isEmpty());
    }

    /**
     * Assert that calls to hasParam() will proxy to the internal parameters collection
     */
    public function testHasParamWillProxyToInternalParametersCollection(): void
    {
        $immutableParameters = new ImmutableParameters(
            new Parameters(
                [
                    'foo' => 123,
                    'bar' => true,
                ]
            )
        );

        $this->assertTrue($immutableParameters->hasParam('foo'));
        $this->assertTrue($immutableParameters->hasParam('bar'));
        $this->assertFalse($immutableParameters->hasParam('baz'));
    }

    /**
     * Assert that calls to getParam() will proxy to the internal parameters collection
     */
    public function testGetParamWillProxyToInternalParametersCollection(): void
    {
        $immutableParameters = new ImmutableParameters(
            new Parameters(
                [
                    'foo' => 456,
                    'bar' => 'Hello World!',
                ]
            )
        );

        $this->assertSame(456, $immutableParameters->getParam('foo'));
        $this->assertSame('Hello World!', $immutableParameters->getParam('bar'));

        $this->assertNull($immutableParameters->getParam('baz'));
        $this->assertFalse($immutableParameters->getParam('baz', false));
        $this->assertSame(123, $immutableParameters->getParam('test', 123));
    }

    /**
     * Assert that calls to removeParam() will NOT modify the internal parameters
     */
    public function testRemoveParamWillReturnFalse(): void
    {
        $params = ['test' => 123];

        $immutableParameters = new ImmutableParameters(new Parameters($params));

        $this->assertFalse($immutableParameters->removeParam('test'));
        $this->assertSame($params, $immutableParameters->getParams());
    }

    /**
     * Assert that calls to getKeys() will return the keys of the internal parameters collection
     */
    public function testGetKeysWillProxyToInternalParametersCollection(): void
    {
        $params = [
            'foo' => 'Hello',
            'bar' => 'test',
            'baz' => 123,
        ];

        $immutableParameters = new ImmutableParameters(new Parameters($params));

        $this->assertSame(array_keys($params), $immutableParameters->getKeys());
    }

    /**
     * Assert that calls to getValues() will return the values of the internal parameters collection
     */
    public function testGetValuesWillProxyToInternalParametersCollection(): void
    {
        $params = [
            'foo' => 'Hello',
            'bar' => 'test',
            'baz' => 123,
        ];

        $immutableParameters = new ImmutableParameters(new Parameters($params));

        $this->assertSame(array_values($params), $immutableParameters->getValues());
    }

    /**
     * Assert that calls to getValues() will return the values of the internal parameters collection
     */
    public function testGetIteratorWillProxyToInternalParametersCollection(): void
    {
        $iterator = new \ArrayIterator(
            [
                'foo' => 'Hello',
                'bar' => 'test',
                'baz' => 123,
            ]
        );

        $this->parameters->expects($this->once())
            ->method('getIterator')
            ->willReturn($iterator);

        $immutableParameters = new ImmutableParameters($this->parameters);

        $this->assertSame($iterator, $immutableParameters->getIterator());
    }

    /**
     * Assert that calls to offsetExists() will test the keys of the internal parameters collection
     */
    public function testOffsetExistsWillProxyToInternalParametersCollection(): void
    {
        $params = [
            'foo' => 'Hello',
            'bar' => 'test',
            'baz' => 123,
        ];

        $immutableParameters = new ImmutableParameters(new Parameters($params));

        $this->assertTrue(isset($immutableParameters['foo']));
        $this->assertTrue(isset($immutableParameters['bar']));
        $this->assertFalse(isset($immutableParameters['test']));
    }

    /**
     * Assert that calls to offsetExists() will test the keys of the internal parameters collection
     */
    public function testOffsetGetWillProxyToInternalParametersCollection(): void
    {
        $params = [
            'foo' => 'testing',
            'bar' => 999,
            'baz' => 3.14,
        ];

        $immutableParameters = new ImmutableParameters(new Parameters($params));

        $this->assertSame($params['foo'], $immutableParameters['foo']);
        $this->assertSame($params['bar'], $immutableParameters['bar']);
        $this->assertSame($params['baz'], $immutableParameters['baz']);
        $this->assertNull($immutableParameters['test']);
    }
}
