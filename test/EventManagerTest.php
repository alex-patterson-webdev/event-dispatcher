<?php

namespace ArpTest\EventManager;

use Arp\EventManager\Event;
use Arp\EventManager\EventInterface;
use Arp\EventManager\EventManager;
use Arp\EventManager\EventManagerInterface;
use Arp\EventManager\EventSubscriberInterface;
use Arp\EventManager\Exception\InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;

/**
 * EventManagerTest
 *
 * @package ArpTest\EventManager
 */
class EventManagerTest extends TestCase
{
    /**
     * testImplementsEventManagerInterface
     *
     * Ensure that the event manager implements EventManagerInterface.
     *
     * @test
     */
    public function testImplementsEventManagerInterface()
    {
        $eventManager = new EventManager();

        $this->assertInstanceOf(EventManagerInterface::class, $eventManager);
    }

    /**
     * testCreateEvent
     *
     * Ensure that the createEvent() method will return the configured event instance.
     *
     * @param string  $name
     * @param mixed   $context
     * @param array   $data
     * @param string  $eventClassName
     *
     * @dataProvider getCreateEventData
     * @test
     */
    public function testCreateEvent($name, array $data = [], $context = null, $eventClassName = null)
    {
        $eventManager = new EventManager();

        if (null === $eventClassName) {
            $eventClassName = Event::class;
        } else {
            $eventManager->setEventClassName($eventClassName);
        }

        $event = $eventManager->createEvent($name, $data, $context);

        $this->assertInstanceOf(EventInterface::class, $event);
        $this->assertInstanceOf($eventClassName, $event);

        $this->assertSame($name, $event->getName());
        $this->assertSame($data, $event->getData());
        $this->assertSame($context, $event->getContext());
    }

    /**
     * getCreateEventData
     *
     * @return array
     */
    public function getCreateEventData()
    {
        return [

            // Simple test
            [
                'test',
                [
                    'hello' => 'world',
                ],
            ],

            [
                'hello.event.name',
                [
                    'foo' => 'bar',
                ],
                new \stdClass
            ],

        ];
    }

    /**
     * testAttachSubscriber
     *
     * Test that the event manager is passed to the subscriber's subscribe event when calling attachSubscriber().
     *
     * @test
     */
    public function testAttachSubscriber()
    {
        $eventManager = new EventManager();

        /** @var EventSubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockForAbstractClass(EventSubscriberInterface::class);

        $subscriber->expects($this->once())
            ->method('subscribe')
            ->with($eventManager);

        $eventManager->attachSubscriber($subscriber);
    }

    /**
     * testAttachListener
     *
     * Ensure that the listener is correctly attached when calling attachListener.
     *
     * @param string    $name      The name of the event that the listener should be attached to.
     * @param callable  $listener  The event listener that should be attached.
     * @param int      $priority   The optional event priority.
     *
     * @dataProvider getAttachListenerData
     * @test
     */
    public function testAttachListener($name, callable $listener, $priority = 1)
    {
        /** @var EventManager|MockObject $eventManager */
        $eventManager = $this->getMockBuilder(EventManager::class)
            ->setMethods(['getQueue'])
            ->getMock();

        /** @var \SplPriorityQueue|MockObject $queue */
        $queue = $this->createMock(\SplPriorityQueue::class);

        $eventManager->expects($this->once())
            ->method('getQueue')
            ->with($name)
            ->willReturn($queue);

        $queue->expects($this->once())
            ->method('insert')
            ->with($listener, $priority);

        $eventManager->attachListener($name, $listener, $priority);
    }

    /**
     * getAttachListenerData
     *
     * @return array
     */
    public function getAttachListenerData()
    {
        return [
            [
                'test.event',
                function (EventInterface $event) {
                    $sum = 1+1;
                }
            ]
        ];
    }

    /**
     * testTriggerWillCreateEventAndProxyToTriggerEvent
     *
     * @param string $name
     * @param array  $params
     * @param mixed  $context
     *
     * @dataProvider getTriggerWillCreateEventAndProxyToTriggerEventData
     * @test
     */
    public function testTriggerWillCreateEventAndProxyToTriggerEvent($name, array $params = [], $context = null)
    {
        /** @var EventManager|MockObject $eventManager */
        $eventManager = $this->getMockBuilder(EventManager::class)
            ->setMethods(['createEvent', 'triggerEvent'])
            ->getMock();

        /** @var EventInterface|MockObject $event */
        $event = $this->getMockForAbstractClass(EventInterface::class);

        $eventManager->expects($this->once())
            ->method('createEvent')
            ->with($name, $params, $context)
            ->willReturn($event);

        $eventManager->expects($this->once())
            ->method('triggerEvent')
            ->with($event);

        $eventManager->trigger($name, $params, $context);
    }

    /**
     * getTriggerWillCreateEventAndProxyToTriggerEventData
     *
     * @return array
     */
    public function getTriggerWillCreateEventAndProxyToTriggerEventData()
    {
        return [

            [
                'foo.event.name'
            ],

            [
                'foo.another.name',
                [
                    'foo' => 'bar',
                ]
            ],

            [
                'bar.event.name',
                [
                    'bar' => 'baz',
                    234   => 556,
                ],
                new \stdClass()
            ],

        ];
    }

    /**
     * testSetEventClassNameWillThrowInvalidArgumentException
     *
     * Ensure that an InvalidArgumentException is thrown if the $eventClassName is of an invalid type.
     *
     * @param string $eventClassName  The event class name that should be tested.
     *
     * @dataProvider getSetEventClassNameWillThrowInvalidArgumentExceptionData
     * @test
     */
    public function testSetEventClassNameWillThrowInvalidArgumentException($eventClassName)
    {
        $eventManager = new EventManager();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'The \'eventClassName\' argument must be an object of type \'%s\'; \'%s\' provided in \'%s::%s\'.',
            EventInterface::class,
            (is_string($eventClassName) ? $eventClassName : gettype($eventClassName)),
            EventManager::class,
            'setEventClassName'
        ));

        $eventManager->setEventClassName($eventClassName);
    }

    /**
     * getSetEventClassNameWillThrowInvalidArgumentExceptionData
     *
     * @return array
     */
    public function getSetEventClassNameWillThrowInvalidArgumentExceptionData()
    {
        return [
            [
                'foo',
            ],

            [
                \stdClass::class,
            ]
        ];
    }

    /**
     * testSetEventClassName
     *
     * Test that the event class name can be set.
     *
     * @param string $eventClassName
     *
     * @dataProvider getSetEventClassNameData
     * @test
     */
    public function testSetEventClassName($eventClassName)
    {
        $eventManager = new EventManager();

        $eventManager->setEventClassName($eventClassName);

        $this->assertAttributeEquals($eventClassName, 'eventClassName', $eventManager);
    }

    /**
     * getSetEventClassNameData
     *
     * @return array
     */
    public function getSetEventClassNameData()
    {
        return [
            [
                Event::class,
            ]
        ];
    }

    /**
     * testTriggerEventWillThrowInvalidArgumentExceptionIfNoNameIsSet
     *
     * Ensure that a InvalidArgumentException is thrown if passing an $event instance without a name to triggerEvent().
     *
     * @test
     */
    public function testTriggerEventWillThrowInvalidArgumentExceptionIfNoNameIsSet()
    {
        $eventManager = new EventManager();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Unable to trigger event for instance that has no name in %s::%s',
            EventManager::class,
            'triggerEvent'
        ));

        /** @var EventInterface|MockObject $event */
        $event = $this->getMockForAbstractClass(EventInterface::class);

        $event->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $eventManager->triggerEvent($event);
    }
    
    /**
     * testTriggerEvent
     *
     * Ensure that calls to triggerEvent are correctly executed.
     *
     * @param string $name
     * @param array  $listeners
     *
     * @dataProvider getTriggerEventData
     * @test
     */
    public function testTriggerEvent($name, array $listeners = [])
    {
        $eventManager = new EventManager();

        /** @var EventInterface|MockObject $event */
        $event = $this->getMockForAbstractClass(EventInterface::class);

        $event->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        $event->expects($this->exactly(count($listeners)))
            ->method('propagate')
            ->willReturn(true);

        foreach($listeners as $listener) {
            $eventManager->attachListener($name, $listener);
        }

        $eventManager->triggerEvent($event);
    }

    /**
     * getTriggerEventData
     *
     * @return array
     */
    public function getTriggerEventData()
    {
        return [
            [
                'foo.event',
                [
                    function($e) {
                        $test = 1;
                    },
                    function($e) {
                        $test = 2;
                    },
                    function($e) {
                        $test = 3;
                    },
                ]
            ]
        ];
    }

}