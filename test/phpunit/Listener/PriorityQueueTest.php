<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\PriorityQueue;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\EventDispatcher\Listener
 */
final class PriorityQueueTest extends TestCase
{
    /**
     * Assert that the class extends from the \SplPriorityQueue.
     *
     * @covers \Arp\EventDispatcher\Listener\PriorityQueue
     */
    public function testExtendsSplPriorityQueue(): void
    {
        $priorityQueue = new PriorityQueue();

        $this->assertInstanceOf(\SplPriorityQueue::class, $priorityQueue);
    }

    /**
     * Assert that the priority queue's comparision returns the expeceted result when provided with various
     * different priority values.
     *
     * @param mixed $priority1
     * @param mixed $priority2
     *
     * @dataProvider getCompareData
     *
     * @covers \Arp\EventDispatcher\Listener\PriorityQueue::compare
     */
    public function testCompare($priority1, $priority2): void
    {
        $priorityQueue = new PriorityQueue();

        $this->assertSame(
            ($priority1 <=> $priority2),
            $priorityQueue->compare($priority1, $priority2)
        );
    }

    /**
     * @return array
     */
    public function getCompareData(): array
    {
        return [
            [1, 1],
            [100, 200],
            [300, 1],
            [2345, 999],
            [1, 1],
            [1000,1000],
            [
                [1, 100],
                [1, 100],
            ],
            [
                [1000, 12398612],
                [1000, 92398612],
            ],
            [
                [1239840, 100],
                [10354561, 1123123],
            ],
        ];
    }
}
