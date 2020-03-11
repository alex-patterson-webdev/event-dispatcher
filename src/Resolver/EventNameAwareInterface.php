<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Resolver;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
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
