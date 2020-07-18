<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

use Arp\EventDispatcher\Resolver\EventNameAwareInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
class NamedEvent extends AbstractEvent implements EventNameAwareInterface
{
    /**
     * @var string
     */
    protected $eventName;

    /**
     * @param string $eventName
     * @param array  $params
     */
    public function __construct(string $eventName, array $params = [])
    {
        parent::__construct($params);

        $this->eventName = $eventName;
    }

    /**
     * Return the event name.
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }
}
