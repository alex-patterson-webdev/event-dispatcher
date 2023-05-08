<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @extends \SplPriorityQueue<mixed, mixed>
 */
final class PriorityQueue extends \SplPriorityQueue
{
    public function compare(mixed $priority1, mixed $priority2): int
    {
        return ($priority1 <=> $priority2);
    }
}
