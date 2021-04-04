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
}
