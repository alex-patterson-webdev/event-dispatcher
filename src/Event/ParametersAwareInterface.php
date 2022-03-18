<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
interface ParametersAwareInterface
{
    /**
     * Set the parameters collection
     *
     * @param ParametersInterface<mixed> $params
     */
    public function setParameters(ParametersInterface $params): void;

    /**
     * Return the parameters collection
     *
     * @return ParametersInterface<mixed>
     */
    public function getParameters(): ParametersInterface;

    /**
     * Return a single parameter value by $name or if not found $default
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null);

    /**
     * Set a single parameter value by $name
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParam(string $name, $value): void;
}
