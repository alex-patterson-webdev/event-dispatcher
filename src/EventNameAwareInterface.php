<?php

namespace Arp\EventManager;

/**
 * EventNameAwareInterface
 *
 * @package Arp\EventManager
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
