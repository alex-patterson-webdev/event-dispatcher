<?php

namespace Arp\EventManager;

/**
 * EventSubscriberInterface
 *
 * Class that adds new event listeners to the provided event manager.
 *
 * @package Arp\EventDispatcher
 */
interface EventSubscriberInterface
{
    /**
     * subscribe
     *
     * Attach events to the provided event manager.
     *
     * @param EventManagerInterface $eventManager  The event manager that should be attached to.
     *
     * @return mixed
     */
    public function subscribe(EventManagerInterface $eventManager);
}