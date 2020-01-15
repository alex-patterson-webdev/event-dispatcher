<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * AbstractEvent
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
abstract class AbstractEvent
{
    /**
     * @var ParametersInterface
     */
    protected $params;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setParameters(new Parameters($params));
    }

    /**
     * Return the parameters collection
     *
     * @return ParametersInterface
     */
    public function getParameters() : ParametersInterface
    {
        return $this->params;
    }

    /**
     * setParameters
     *
     * @param ParametersInterface $params
     */
    public function setParameters(ParametersInterface $params) : void
    {
        $this->params = $params;
    }
}
