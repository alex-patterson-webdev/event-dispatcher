<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Factory;

use Arp\EventDispatcher\Factory\EventDispatcherFactory;
use Arp\Factory\FactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Factory
 */
final class EventDispatcherFactoryTest extends TestCase
{
    /**
     * Assert that the factory implements FactoryInterface.
     *
     * @covers \Arp\EventDispatcher\Factory\EventDispatcherFactory
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new EventDispatcherFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }
}