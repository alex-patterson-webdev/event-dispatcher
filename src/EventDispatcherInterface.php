<?php

namespace Arp\EventManager;

/**
 * EventDispatcherInterface
 *
 * @note This is a clone of the PSR-14 event dispatcher project interface.
 *       When this project supports PHP 7.2 this interface will extend the PSR implementation and be
 *       marked as deprecated.
 *
 * @package Arp\EventDispatcher
 */
interface EventDispatcherInterface
{
    /**
     * dispatch
     *
     * Dispatch the event listeners, passing $event to each of them.
     *
     * @param object $event
     *
     * @return object
     */
    public function dispatch($event);

}