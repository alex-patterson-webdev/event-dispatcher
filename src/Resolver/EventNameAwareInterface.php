<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

/**
 * EventNameAwareInterface
 *
 * @package Arp\EventDispatcher\Resolver
 */
interface EventNameAwareInterface
{
    /**
     * getEventName
     *
     * @return string
     */
    public function getEventName() : string;
}
