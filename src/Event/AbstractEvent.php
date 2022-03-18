<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
abstract class AbstractEvent implements ParametersAwareInterface
{
    use ParametersAwareTrait;

    /**
     * @param ParametersInterface<mixed>|array<mixed>|mixed $params
     */
    public function __construct($params = [])
    {
        if (!$params instanceof ParametersInterface) {
            $params = new Parameters(is_array($params) ? $params : []);
        }

        $this->setParameters($params);
    }
}
