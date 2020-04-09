<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
final class PriorityQueue extends \SplPriorityQueue
{
    /**
     * Compare the priorities.
     *
     * @param mixed $priority1
     * @param mixed $priority2
     *
     * @return int
     */
    public function compare($priority1, $priority2): int
    {
        return ($priority1 <=> $priority2);
    }
}
