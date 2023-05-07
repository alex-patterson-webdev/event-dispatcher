<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

use Arp\EventDispatcher\Resolver\EventNameAwareInterface;

class NamedEvent extends AbstractEvent implements EventNameAwareInterface
{
    protected string $eventName;

    public function __construct(string $eventName, array $params = [])
    {
        parent::__construct($params);

        $this->eventName = $eventName;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }
}
