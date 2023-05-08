<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Resolver\Exception;

use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\EventDispatcher\Resolver\Exception\EventNameResolverException
 */
final class EventNameResolverExceptionTest extends TestCase
{
    public function testImplementsException(): void
    {
        $this->assertInstanceOf(\Exception::class, new EventNameResolverException());
    }
}
