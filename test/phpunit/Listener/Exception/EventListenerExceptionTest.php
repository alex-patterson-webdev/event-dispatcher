<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener\Exception;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener\Exception
 */
final class EventListenerExceptionTest extends TestCase
{
    /**
     * Assert that the EventListenerException extends from Exception.
     *
     * @covers \Arp\EventDispatcher\Listener\Exception\EventListenerException
     */
    public function testImplementsException(): void
    {
        $exception = new EventListenerException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
