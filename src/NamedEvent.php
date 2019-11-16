<?php

namespace Arp\EventDispatcher;

use Arp\EventDispatcher\Resolver\EventNameAwareInterface;

/**
 * NamedEvent
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher
 */
class NamedEvent extends AbstractEvent implements EventNameAwareInterface
{
    /**
     * @var string
     */
    protected $eventName;

    /**
     * __construct
     *
     * @param string $eventName
     * @param array  $params
     */
    public function __construct(string $eventName, array $params = [])
    {
        $this->eventName = $eventName;

        $this->setParams($params);
    }

    /**
     * getEventName
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }
}