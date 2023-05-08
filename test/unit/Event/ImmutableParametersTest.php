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
 */
final class ImmutableParametersTest extends TestCase
{
    private ParametersInterface&MockObject $parameters;

    public function setUp(): void
    {
        $this->parameters = $this->createMock(ParametersInterface::class);
    }

    public function testImplementsParametersInterface(): void
    {
        $params = new ImmutableParameters($this->parameters);

        $this->assertInstanceOf(ParametersInterface::class, $params);
    }

    public function testImplementsArrayAccess(): void
    {
        $params = new ImmutableParameters($this->parameters);

        $this->assertInstanceOf(\ArrayAccess::class, $params);
    }

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

    public function testCountWillReturnParameterCount(): void
    {
        $parameters = new ImmutableParameters($this->parameters);

        $this->parameters->expects($this->once())
            ->method('count')
            ->willReturn(5);

        $this->assertSame(5, $parameters->count());
    }

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

    public function testRemoveParamWillReturnFalse(): void
    {
        $params = ['test' => 123];

        $immutableParameters = new ImmutableParameters(new Parameters($params));

        $this->assertFalse($immutableParameters->removeParam('test'));
        $this->assertSame($params, $immutableParameters->getParams());
    }

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

    public function testSetParamPerformsNoModifications(): void
    {
        $data = [
            'foo' => 123,
            'bar' => 'test',
        ];

        $params = new ImmutableParameters(new Parameters($data));

        $this->assertSame($data, $params->getParams());

        $params->setParam('foo', true);
        $params->setParam('baz', 'Testing setter');

        $this->assertSame(123, $params->getParam('foo'));
        $this->assertFalse($params->hasParam('baz'));
        $this->assertSame($data, $params->getParams());
    }

    public function testOffsetSetParamPerformsNoModifications(): void
    {
        $data = [
            'foo' => 456,
            'bar' => 'test123',
        ];

        $params = new ImmutableParameters(new Parameters($data));

        $this->assertSame($data, $params->getParams());

        $params->offsetSet('foo', true);
        $params->offsetSet('baz', 'Testing setter');

        $this->assertSame(456, $params->getParam('foo'));
        $this->assertFalse($params->hasParam('baz'));
        $this->assertSame($data, $params->getParams());
    }

    public function testRemoveParamsWillPerformsNoModifications(): void
    {
        $data = [
            'foo' => 123,
            'bar' => 'test',
            'baz' => true,
        ];

        $params = new ImmutableParameters(new Parameters($data));

        $params->removeParams(['foo', 'baz']);
        $params->removeParams(['baz']);

        $this->assertTrue($params->hasParam('foo'));
        $this->assertSame($data, $params->getParams());
    }

    public function testOffsetUnsetWillPerformsNoModifications(): void
    {
        $data = [
            'foo' => 123,
            'bar' => 'test',
            'baz' => true,
        ];

        $params = new ImmutableParameters(new Parameters($data));

        $params->offsetUnset('foo');
        $params->offsetUnset('baz');

        $this->assertTrue($params->hasParam('foo'));
        $this->assertTrue($params->hasParam('baz'));
        $this->assertSame($data, $params->getParams());
    }
}
