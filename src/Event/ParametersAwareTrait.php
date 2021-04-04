<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
trait ParametersAwareTrait
{
    /**
     * @var ParametersInterface<mixed>
     */
    protected ParametersInterface $params;

    /**
     * Set the parameters collection
     *
     * @param ParametersInterface<mixed> $params
     */
    public function setParameters(ParametersInterface $params): void
    {
        $this->params = $params;
    }

    /**
     * Return the parameters collection
     *
     * @return ParametersInterface<mixed>
     */
    public function getParameters(): ParametersInterface
    {
        return $this->params;
    }

    /**
     * Return a single parameter value by $name or if not found $default
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return $this->params->getParam($name, $default);
    }

    /**
     * Set a single parameter value by $name
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParam(string $name, $value): void
    {
        $this->params->setParam($name, $value);
    }
}
