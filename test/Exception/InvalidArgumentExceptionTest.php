<?php

namespace ArpTest\EventDispatcher\Exception;

use Arp\EventDispatcher\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * InvalidArgumentExceptionTest
 *
 * @package ArpTest\EventDispatcher\Exception
 */
class InvalidArgumentExceptionTest extends TestCase
{
    /**
     * Assert that the EventDispatcherException extends \Exception.
     *
     * @test
     */
    public function testImplementsException() : void
    {
        $exception = new InvalidArgumentException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
