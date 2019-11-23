<?php

namespace Arp\EventDispatcher\Resolver;

use Arp\EventDispatcher\Exception\InvalidArgumentException;

/**
 * EventNameResolverInterface
 *
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
     * @throws InvalidArgumentException  If the provided $event is not a string or object.
     */
    public function resolveEventName($event) : string;
}
