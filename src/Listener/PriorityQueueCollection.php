<?php

namespace Arp\EventManager;

/**
 * PriorityQueueManager
 *
 * @package Arp\EventManagers
 */
class PriorityQueueCollection implements ListenerCollectionInterface
{
    /**
     * $listeners
     *
     * @var \SplPriorityQueue[]
     */
    protected $listeners = [];

    /**
     * addListener
     *
     * @param          $eventName
     * @param callable $listener
     * @param int      $priority
     *
     * @return
     */
    public function addListener($eventName, callable $listener, $priority = 1)
    {

    }

    /**
     * getListenersForEvent
     *
     * Return a collection of event listeners for the provided event.
     *
     * @param object $event The event that will be triggered.
     *
     * @return callable[]  A collection of event listeners to execute.
     */
    public function getListenersForEvent($event)
    {

    }

}