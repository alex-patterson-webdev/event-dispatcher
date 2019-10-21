<?php

namespace Arp\EventManager;

/**
 * StoppableEventInterface
 *
 * Cloned PSR-14 interface which cannot be used in PHP 5.6.
 *
 * @note This will be deprecated and extended by the PSR implementation when this project supports PHP 7.2+.
 *
 * @package Arp\EventDispatcher
 */
interface StoppableEventInterface
{
    /**
     * isPropagationStopped
     *
     * @return boolean
     */
    public function isPropagationStopped();
}