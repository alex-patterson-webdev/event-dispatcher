<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Event;

use Arp\EventDispatcher\Event\NamedEvent;
use Arp\EventDispatcher\Event\ParametersInterface;
use Arp\EventDispatcher\Resolver\EventNameAwareInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Event
 */
final class NamedEventTest extends TestCase
{
    /**
     * Assert that the class implements EventNameAwareInterface.
     *
     * @covers \Arp\EventDispatcher\Event\NamedEvent
     */
    public function testImplementsEventNameAwareInterface(): void
    {
        $namedEvent = new NamedEvent('test');

        $this->assertInstanceOf(EventNameAwareInterface::class, $namedEvent);
    }

    /**
     * Assert that getEventName() will return the name of the event.
     *
     * @covers \Arp\EventDispatcher\Event\NamedEvent::getEventName
     */
    public function testGetEventNameWillReturnEventName(): void
    {
        $namedEvent = new NamedEvent('foo');

        $this->assertSame('foo', $namedEvent->getEventName());
    }

    /**
     * Assert that a empty parameters collection is set by default.
     *
     * @covers \Arp\EventDispatcher\Event\AbstractEvent::__construct
     * @covers \Arp\EventDispatcher\Event\NamedEvent::setParameters
     * @covers \Arp\EventDispatcher\Event\NamedEvent::getParameters
     */
    public function testSetAndGetParameters(): void
    {
        $params = ['foo' => 'bar'];

        $namedEvent = new NamedEvent('foo', $params);

        $this->assertSame($params, $namedEvent->getParameters()->getParams());

        /** @var ParametersInterface|MockObject $parameters */
        $parameters = $this->getMockForAbstractClass(ParametersInterface::class);

        $namedEvent->setParameters($parameters);

        $this->assertSame($parameters, $namedEvent->getParameters());
    }
}
