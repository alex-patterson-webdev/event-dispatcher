<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

use Arp\EventDispatcher\Resolver\Exception\EventNameResolverException;

interface EventNameResolverInterface
{
    /**
     * @throws EventNameResolverException  If the provided $event is not a string or object.
     */
    public function resolveEventName(string|object $event): string;
}
