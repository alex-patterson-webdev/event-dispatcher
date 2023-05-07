<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\EventNameAwareInterface;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\EventDispatcher\Resolver\EventNameResolver
 */
final class EventNameResolverTest extends TestCase
{
    public function testImplementsEventNameResolverInterface(): void
    {
        $this->assertInstanceOf(EventNameResolverInterface::class, new EventNameResolver());
    }

    /**
     * @dataProvider getResolveEventNameWillResolveStringEventNameData
     */
    public function testResolveEventNameWillResolveStringEventName(string $eventName): void
    {
        $resolver = new EventNameResolver();

        $this->assertSame($eventName, $resolver->resolveEventName($eventName));
    }

    /**
     * @return array<mixed>
     */
    public function getResolveEventNameWillResolveStringEventNameData(): array
    {
        return [
            ['FooEvent'],
            ['BarEvent'],
            ['HelloWorld'],
        ];
    }

    public function testResolveEventNameWillResolveObjectEventName(): void
    {
        $resolver = new EventNameResolver();

        $event = new \stdClass();

        $this->assertSame(get_class($event), $resolver->resolveEventName($event));
    }

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
}
