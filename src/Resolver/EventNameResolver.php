<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Resolver
 */
final class EventNameResolver implements EventNameResolverInterface
{
    /**
     * Resolve an event instance into an event name.
     *
     * @param object|string $event The event that should be resolved.
     *
     * @return string
     *
     * @throws EventNameResolverException  If the provided $event is not a string or object.
     */
    public function resolveEventName($event): string
    {
        if (is_string($event)) {
            return $event;
        }

        if (!is_object($event)) {
            throw new EventNameResolverException(sprintf(
                'The \'event\' argument must be of type \'string\' or \'object\'; \'%s\' provided in \'%s\'.',
                gettype($event),
                __METHOD__
            ));
        }

        if ($event instanceof EventNameAwareInterface) {
            return $event->getEventName();
        }

        return get_class($event);
    }
}
