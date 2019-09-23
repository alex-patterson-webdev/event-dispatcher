<?php

namespace Arp\EventManager;

/**
 * EventManagerAwareTrait
 *
 * @package Arp\EventManager
 */
trait EventManagerAwareTrait
{
    /**
     * $eventManager
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * getEventManager
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->eventManager = new EventManager();
        }

        return $this->eventManager;
    }

    /**
     * setEventManager
     *
     * Set the event manager instance.
     *
     * @param EventManagerInterface $eventManager  The event manager to set.
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}