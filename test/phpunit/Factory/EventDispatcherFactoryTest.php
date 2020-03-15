<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Factory;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Factory\EventDispatcherFactory;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\Factory\Exception\FactoryException;
use Arp\Factory\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;

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

    /**
     * Assert that create() will thrown a FactoryException if the provided 'listener_provider' configuration option is
     * not of type ListenerProviderInterface.
     *
     * @throws FactoryException
     *
     * @covers \Arp\EventDispatcher\Factory\EventDispatcherFactory::create
     */
    public function testCreateWillThrowFactoryExceptionIfTheProvidedListenerProviderIsInvalid(): void
    {
        $factory = new EventDispatcherFactory();

        $listenerProvider = new \stdClass();

        $config = [
            'listener_provider' => $listenerProvider,
        ];

        $this->expectException(FactoryException::class);
        $this->expectExceptionMessage(sprintf(
            'The \'listener_provider\' configuration option must be of type \'%s\'; \'%s\' provided in \'%s\'',
            ListenerProviderInterface::class,
            is_object($listenerProvider) ? get_class($listenerProvider) : gettype($listenerProvider),
            EventDispatcherFactory::class
        ));

        $factory->create($config);
    }

    /**
     * Assert that the create() method will return a valid EventDispatcher instance that respects the provided $config.
     *
     * @param array $config The test configuration options.
     *
     * @throws FactoryException
     *
     * @covers \Arp\EventDispatcher\Factory\EventDispatcherFactory::__construct
     * @covers \Arp\EventDispatcher\Factory\EventDispatcherFactory::create
     *
     * @dataProvider getCreateData
     */
    public function testCreate(array $config): void
    {
        /** @var FactoryInterface|MockObject $listenerProviderFactory */
        $listenerProviderFactory = $this->getMockForAbstractClass(FactoryInterface::class);

        $factory = new EventDispatcherFactory($listenerProviderFactory, EventDispatcher::class);

        $listenerProviderConfig = $config['listener_provider'] ?? [];

        if (is_array($listenerProviderConfig)) {
            /** @var ListenerProviderInterface|MockObject $listenerProvider */
            $listenerProvider = $this->getMockForAbstractClass(ListenerProviderInterface::class);

            $listenerProviderFactory->expects($this->once())
                ->method('create')
                ->with($listenerProviderConfig)
                ->willReturn($listenerProvider);
        }

        $eventDispatcher = $factory->create($config);

        $this->assertSame(
            get_class($eventDispatcher),
            $config['class_name'] ?? EventDispatcher::class,
            'The event dispatcher class type does not match the type passed in with option class_name'
        );
    }

    /**
     * @return array
     */
    public function getCreateData(): array
    {
        return [
            [
                []
            ],

            [
                [
                    'class_name' => EventDispatcher::class,
                ]
            ],

            [
                [
                    'listener_provider' => [
                        'class_name' => ListenerProvider::class,
                    ]
                ]
            ]
        ];
    }
}
