<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Factory\Resolver;

use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\Factory\FactoryInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Factory\Resolver
 */
class EventNameResolverFactory implements FactoryInterface
{
    /**
     * Create a new service.
     *
     * @param array $config The optional factory configuration options.
     *
     * @return EventNameResolverInterface
     */
    public function create(array $config = []): EventNameResolverInterface
    {
        return new EventNameResolver();
    }
}
