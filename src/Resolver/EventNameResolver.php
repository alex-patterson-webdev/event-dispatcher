<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;

final class EventNameResolver implements EventNameResolverInterface
{
    /**
     * @throws EventNameResolverException  If the provided $event is not a string or object.
     */
    public function resolveEventName(string|object $event): string
    {
        if (is_string($event)) {
            return $event;
        }

        if (!is_object($event)) {
            throw new EventNameResolverException(
                sprintf(
                    'The \'event\' argument must be of type \'string\' or \'object\'; \'%s\' provided in \'%s\'.',
                    gettype($event),
                    __METHOD__
                )
            );
        }

        if ($event instanceof EventNameAwareInterface) {
            return $event->getEventName();
        }

        return get_class($event);
    }
}
