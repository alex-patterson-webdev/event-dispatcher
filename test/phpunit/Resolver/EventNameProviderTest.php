<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\EventNameAwareInterface;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Resolver
 */
final class EventNameProviderTest extends TestCase
{
    /**
     * Assert that the EventNameResolver implements EventNameResolverInterface
     *
     * @covers \Arp\EventDispatcher\Resolver\EventNameResolver
     */
    public function testImplementsEventNameResolverInterface(): void
    {
        $resolver = new EventNameResolver();

        $this->assertInstanceOf(EventNameResolverInterface::class, $resolver);
    }

    /**
     * Assert that when providing a string to resolveEventName() we will return the same string.
     *
     * @param string $eventName The event name string to test.
     *
     * @dataProvider getResolveEventNameWillResolveStringEventNameData
     * @covers \Arp\EventDispatcher\Resolver\EventNameResolver::resolveEventName
     */
    public function testResolveEventNameWillResolveStringEventName(string $eventName): void
    {
        $resolver = new EventNameResolver();

        $this->assertSame($eventName, $resolver->resolveEventName($eventName));
    }

    /**
     * @return array
     */
    public function getResolveEventNameWillResolveStringEventNameData(): array
    {
        return [
            ['FooEvent'],
            ['BarEvent'],
            ['HelloWorld'],
        ];
    }

    /**
     * Assert that the class name of the object provided to resolveEventName() will return the FQCN.
     *
     * @covers \Arp\EventDispatcher\Resolver\EventNameResolver::resolveEventName
     */
    public function testResolveEventNameWillResolveObjectEventName(): void
    {
        $resolver = new EventNameResolver();

        $event = new \stdClass();

        $this->assertSame(get_class($event), $resolver->resolveEventName($event));
    }

    /**
     * Assert that the event name will resolve to the FQCN of the provided object when calling resolveEventName().
     *
     * @covers \Arp\EventDispatcher\Resolver\EventNameResolver::resolveEventName
     */
    public function testResolveEventNameWillResolveEventNameAwareEventName(): void
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
     * @covers \Arp\EventDispatcher\Resolver\EventNameResolver::resolveEventName
     */
    public function testResolveEventNameWillThrowInvalidArgumentException(): void
    {
        $resolver = new EventNameResolver();

        $event = 1.1; // float is invalid.

        $this->expectException(EventNameResolverException::class);
        $this->expectExceptionMessage(sprintf(
            'The \'event\' argument must be of type \'string\' or \'object\'; \'%s\' provided in \'%s::%s\'.',
            gettype($event),
            EventNameResolver::class,
            'resolveEventName'
        ));

        $resolver->resolveEventName($event);
    }
}
