<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Resolver\Exception;

use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Resolver\Exception
 */
final class EventNameResolverExceptionTest extends TestCase
{
    /**
     * Assert that the EventListenerException extends from Exception.
     *
     * @covers \Arp\EventDispatcher\Resolver\Exception\EventNameResolverException
     */
    public function testImplementsException(): void
    {
        $exception = new EventNameResolverException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
