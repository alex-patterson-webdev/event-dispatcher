<?php

namespace Arp\EventManager;

/**
 * ListenerProviderInterface
 *
 * @note This is a clone of the PSR-14 event-dispatcher interface. When this project moves to PHP 7.2+ this
 *       interface will extended the PSR implementation and be marked as deprecated.
 *
 * @package Arp\EventDispatcher
 */
interface ListenerProviderInterface
{
    /**
     * getListenersForEvent
     *
     * Return a collection of event listeners for the provided event.
     *
     * @param object  $event  The event that will be triggered.
     *
     * @return callable[]  A collection of event listeners to execute.
     */
    public function getListenersForEvent($event);

}