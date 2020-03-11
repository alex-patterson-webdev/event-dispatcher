<?php declare(strict_types=1);

namespace ArpTest\EventDispatcher\Exception;

use Arp\EventDispatcher\Exception\EventDispatcherException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Exception
 */
final class EventDispatcherExceptionTest extends TestCase
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
