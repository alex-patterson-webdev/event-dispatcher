<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Factory;

use Arp\EventDispatcher\Factory\EventDispatcherFactory;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\Factory\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Factory
 */
final class EventDispatcherFactoryTest extends TestCase
{
    /**
     * @var AddableListenerProviderInterface|MockObject
     */
    private $listenerProvider;

    /**
     * Prepare the test case dependencies
     */
    public function setUp(): void
    {
        $this->listenerProvider = $this->getMockForAbstractClass(AddableListenerProviderInterface::class);
    }

    /**
     * Assert that the event dispatcher factory implements FactoryInterface.
     *
     * @covers \Arp\EventDispatcher\Factory\EventDispatcherFactory
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new EventDispatcherFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }
}
