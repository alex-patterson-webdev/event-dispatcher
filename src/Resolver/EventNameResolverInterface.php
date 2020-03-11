<?php declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Resolver
 */
interface EventNameResolverInterface
{
    /**
     * Resolve an event instance into an event name.
     *
     * @param object|string  $event  The event that should be resolved.
     *
     * @return string
     *
     * @throws EventNameResolverException  If the provided $event is not a string or object.
     */
    public function resolveEventName($event) : string;
}
