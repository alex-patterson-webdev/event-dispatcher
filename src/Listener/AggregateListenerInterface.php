<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
interface AggregateListenerInterface
{
    /**
     * Add a number of listeners for different events.
     *
     * @param AddListenerAwareInterface $collection
     */
    public function addListeners(AddListenerAwareInterface $collection): void;
}
