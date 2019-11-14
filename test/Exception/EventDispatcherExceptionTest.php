<?php

namespace ArpTest\EventDispatcher\Exception;

use Arp\EventDispatcher\Exception\EventDispatcherException;
use PHPUnit\Framework\TestCase;

/**
 * EventDispatcherExceptionTest
 *
 * @package ArpTest\EventDispatcher\Exception
 */
class EventDispatcherExceptionTest extends TestCase
{
    /**
     * Assert that the EventDispatcherException extends \Exception.
     *
     * @test
     */
    public function testImplementsException() : void
    {
        $exception = new EventDispatcherException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
