<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

interface EventNameAwareInterface
{
    public function getEventName(): string;
}
