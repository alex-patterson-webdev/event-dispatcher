<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

final class EventNameResolver implements EventNameResolverInterface
{
    public function resolveEventName(string|object $event): string
    {
        if (is_string($event)) {
            return $event;
        }

        if ($event instanceof EventNameAwareInterface) {
            return $event->getEventName();
        }

        return get_class($event);
    }
}
