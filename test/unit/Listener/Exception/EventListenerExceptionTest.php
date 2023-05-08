<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener\Exception;

use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use PHPUnit\Framework\TestCase;

final class EventListenerExceptionTest extends TestCase
{
    public function testImplementsException(): void
    {
        $exception = new EventListenerException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
