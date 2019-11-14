<?php

namespace ArpTest\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\EventDispatcher\Resolver\EventNameAwareInterface;
use Arp\EventDispatcher\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * EventNameProviderTest
 *
 * @package ArpTest\EventDispatcher\Resolver
 */
class EventNameProviderTest extends TestCase
{
    /**
     * Assert that the EventNameResolver implements EventNameResolverInterface
     *
     * @test
     */
    public function testImplementsEventNameResolverInterface() : void
    {
        $resolver = new EventNameResolver();

        $this->assertInstanceOf(EventNameResolverInterface::class, $resolver);
    }

    /**
     * Assert that when providing a string to resolveEventName() we will return the same string.
     *
     * @param string $eventName  The event name string to test.
     *
     * @dataProvider getResolveEventNameWillResolveStringEventNameData
     * @test
     */
    public function testResolveEventNameWillResolveStringEventName(string $eventName) : void
    {
        $resolver = new EventNameResolver();

        $this->assertSame($eventName, $resolver->resolveEventName($eventName));
    }

    /**
     * @return array
     */
    public function getResolveEventNameWillResolveStringEventNameData() : array
    {
        return [
            ['FooEvent'],
            ['BarEvent'],
            ['HelloWorld']
        ];
    }

    /**
     * Assert that the class name of the object provided to resolveEventName() will return the FQCN.
     *
     * @test
     */
    public function testResolveEventNameWillResolveObjectEventName() : void
    {
        $resolver = new EventNameResolver();

        $event = new \stdClass;

        $this->assertSame(get_class($event), $resolver->resolveEventName($event));
    }

    /**
     * Assert that the event name will resolve to the FQCN of the provided object when calling resolveEventName().
     *
     * @test
     */
    public function testResolveEventNameWillResolveEventNameAwareEventName() : void
    {
        $resolver = new EventNameResolver();

        $eventName = 'FooEvent';
        $event = $this->getMockForAbstractClass(EventNameAwareInterface::class);

        $event->expects($this->once())
            ->method('getEventName')
            ->willReturn($eventName);

        $this->assertSame($eventName, $resolver->resolveEventName($event));
    }

    /**
     * Assert that a InvalidArgumentException is thrown when passing an invalid argument to resolveEventName().
     *
     * @test
     */
    public function testResolveEventNameWillThrowInvalidArgumentException() : void
    {
        $resolver = new EventNameResolver();

        $event = 1.1; // float is invalid.

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'The \'event\' argument must be of type \'string\' or \'object\'; \'%s\' provided in \'%s::%s\'.',
            gettype($event),
            EventNameResolver::class,
            'resolveEventName'
        ));

        $resolver->resolveEventName($event);
    }
}
