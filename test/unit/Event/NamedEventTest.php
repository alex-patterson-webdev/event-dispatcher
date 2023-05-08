<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Event;

use Arp\EventDispatcher\Event\NamedEvent;
use Arp\EventDispatcher\Event\ParametersInterface;
use Arp\EventDispatcher\Resolver\EventNameAwareInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\EventDispatcher\Event\NamedEvent
 * @covers \Arp\EventDispatcher\Event\AbstractEvent
 */
final class NamedEventTest extends TestCase
{
    /**
     * Assert that the class implements EventNameAwareInterface
     */
    public function testImplementsEventNameAwareInterface(): void
    {
        $namedEvent = new NamedEvent('test');

        $this->assertInstanceOf(EventNameAwareInterface::class, $namedEvent);
    }

    /**
     * Assert that getEventName() will return the name of the event
     */
    public function testGetEventNameWillReturnEventName(): void
    {
        $namedEvent = new NamedEvent('foo');

        $this->assertSame('foo', $namedEvent->getEventName());
    }

    /**
     * Assert that a empty parameters collection is set by default
     */
    public function testSetAndGetParameters(): void
    {
        $params = ['foo' => 'bar'];

        $namedEvent = new NamedEvent('foo', $params);

        $this->assertSame($params, $namedEvent->getParameters()->getParams());

        /** @var ParametersInterface<mixed>&MockObject $parameters */
        $parameters = $this->createMock(ParametersInterface::class);

        $namedEvent->setParameters($parameters);

        $this->assertSame($parameters, $namedEvent->getParameters());
    }

    /**
     * Assert that parameters can be added and fetched from the event instance
     */
    public function testGetAndSetParam(): void
    {
        $event = new NamedEvent('foo');

        $this->assertNull($event->getParam('foo'));
        $this->assertFalse($event->getParam('foo', false));

        $event->setParam('test', 123);
        $event->setParam('hello', true);

        $this->assertSame(123, $event->getParam('test'));
        $this->assertTrue($event->getParam('hello'));
    }
}
