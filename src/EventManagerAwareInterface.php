<?php

namespace Arp\EventManager;

/**
 * EventManagerAwareInterface
 *
 * @package Arp\EventDispatcher
 */
interface EventManagerAwareInterface
{
    /**
     * getEventManager
     *
     * @return EventManagerInterface
     */
    public function getEventManager();

    /**
     * setEventManager
     *
     * Set the event manager instance.
     *
     * @param EventManagerInterface $eventManager  The event manager to set.
     */
    public function setEventManager(EventManagerInterface $eventManager);

}