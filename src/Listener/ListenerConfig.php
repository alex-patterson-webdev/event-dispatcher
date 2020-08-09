<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
class ListenerConfig
{
    /**
     * @var callable
     */
    protected $listener;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @param callable $listener
     * @param string   $eventName
     * @param int      $priority
     */
    public function __construct(callable $listener, string $eventName, int $priority = 1)
    {
        $this->setListener($listener);
        $this->setEventName($eventName);
    }

    /**
     * @return callable
     */
    public function getListener(): callable
    {
        return $this->listener;
    }

    /**
     * @param callable $listener
     */
    public function setListener(callable $listener): void
    {
        $this->listener = $listener;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName(string $eventName): void
    {
        $this->eventName = $eventName;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        if (isset($data['listener'])) {
            $this->setListener($data['listener']);
        }

        if (isset($data['event_name'])) {
            $this->setEventName($data['event_name']);
        }

        if (isset($data['priority'])) {
            $this->setPriority($data['priority']);
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'listener' => $this->listener,
            'event_name' => $this->eventName,
            'priority' => $this->priority,
        ];
    }
}
