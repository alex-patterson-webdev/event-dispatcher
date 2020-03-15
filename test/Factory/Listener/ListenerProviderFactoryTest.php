<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Factory\Listener;

use Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Factory\Listener
 */
final class ListenerProviderFactoryTest extends TestCase
{
    /**
     * Assert that the factory implements FactoryInterface.
     *
     * @covers \Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new ListenerProviderFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * Assert that the create() method will throw a FactoryException if the provided 'event_name_resolver'
     * configuration option is invalid.
     *
     * @throws FactoryException
     *
     * @covers \Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory::create
     */
    public function testCreateWillThrowFactoryExceptionIfTheEventNameResolverStringIsInvalid(): void
    {
        $factory = new ListenerProviderFactory();

        $eventNameResolver = \stdClass::class;

        $config = [
            'event_name_resolver' => $eventNameResolver, // this is invalid!
        ];

        $this->expectException(FactoryException::class);
        $this->expectExceptionMessage(sprintf(
            'The event name resolver must be a class that implements \'%s\'; \'%s\' provided in \'%s\'.',
            EventNameResolverInterface::class,
            $eventNameResolver,
            ListenerProviderFactory::class
        ));

        $factory->create($config);
    }

    /**
     * Assert that the create() method will throw a FactoryException if the configured event name resolver is not of
     * type EventNameResolverInterface.
     *
     * @covers \Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory::create
     */
    public function testCreateWillThrowFactoryExceptionIfTheEventNameResolverIsNotEventNameResolverInterface(): void
    {
        $factory = new ListenerProviderFactory();

        $eventNameResolver = new \stdClass(); // invalid object type...
        $config = [
            'event_name_resolver' => $eventNameResolver,
        ];

        $this->expectException(FactoryException::class);
        $this->expectExceptionMessage(sprintf(
            'The event name resolver must be an object that implements \'%s\'; \'%s\' provided in \'%s\'.',
            EventNameResolverInterface::class,
            is_object($eventNameResolver) ? get_class($eventNameResolver) : gettype($eventNameResolver),
            ListenerProviderFactory::class
        ));

        $factory->create($config);
    }

    /**
     * Assert that the ListenerProvider is returned matching the provided $config options.
     *
     * @covers \Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory::create
     */
    public function testCreate(): void
    {
        $factory = new ListenerProviderFactory();

        $config = [];

        $this->assertInstanceOf(ListenerProvider::class, $factory->create($config));
    }
}
