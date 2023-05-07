<?php

declare(strict_types=1);

namespace ArpTest\EventDispatcher\Listener;

use Arp\EventDispatcher\Listener\PriorityQueue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\EventDispatcher\Listener\PriorityQueue
 */
final class PriorityQueueTest extends TestCase
{
    public function testExtendsSplPriorityQueue(): void
    {
        $priorityQueue = new PriorityQueue();

        $this->assertInstanceOf(\SplPriorityQueue::class, $priorityQueue);
    }

    /**
     * @dataProvider getCompareData
     */
    public function testCompare(mixed $priority1, mixed $priority2): void
    {
        $priorityQueue = new PriorityQueue();

        $this->assertSame(
            ($priority1 <=> $priority2),
            $priorityQueue->compare($priority1, $priority2)
        );
    }

    /**
     * @return array<mixed>
     */
    public function getCompareData(): array
    {
        return [
            [1, 1],
            [100, 200],
            [300, 1],
            [2345, 999],
            [1, 1],
            [1000, 1000],
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
