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
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     */
    public function __construct(string $eventName, callable $listener, int $priority = 1)
    {
        $this->eventName = $eventName;
        $this->listener = $listener;
        $this->priority = $priority;
    }

    /**
     * @return callable
     */
    public function getListener(): callable
    {
        return $this->listener;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
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
     * Compare the priority with another listener config instance.
     *
     * @param ListenerConfig $config
     *
     * @return int
     */
    public function comparePriority(ListenerConfig $config): int
    {
        return ($this->priority <=> $config->getPriority());
    }

    /**
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        if (isset($data['listener']) && is_callable($data['listener'])) {
            $this->listener = $data['listener'];
        }

        if (isset($data['event_name']) && is_string($data['event_name'])) {
            $this->eventName = $data['event_name'];
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
