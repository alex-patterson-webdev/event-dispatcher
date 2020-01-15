<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

use Arp\EventDispatcher\Exception\InvalidArgumentException;

/**
 * EventNameResolver
 *
 * @package Arp\EventDispatcher\Resolver
 */
class EventNameResolver implements EventNameResolverInterface
{
    /**
     * Resolve an event instance into an event name.
     *
     * @param object|string $event The event that should be resolved.
     *
     * @return string
     *
     * @throws InvalidArgumentException  If the provided $event is not a string or object.
     */
    public function resolveEventName($event): string
    {
        if (is_string($event)) {
            return $event;
        }

        if (! is_object($event)) {
            throw new InvalidArgumentException(sprintf(
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
