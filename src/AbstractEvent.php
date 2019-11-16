<?php

namespace Arp\EventDispatcher;

/**
 * AbstractEvent
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher
 */
abstract class AbstractEvent
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * Check if a parameter exists within the collection.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasParam(string $name) : bool
    {
        return array_key_exists($name, $this->params);
    }

    /**
     * Return a parameter by $name; $default will be returned if the value cannot be found.
     *
     * @param string $name      The name of the parameter to search for.
     * @param null   $default   The default fallback value if not found.
     *
     * @return mixed|null
     */
    public function getParam(string $name, $default = null)
    {
        if ($this->hasParam($name)) {
            return $this->params[$name];
        }
        return $default;
    }

    /**
     * Return the parameters collection.
     *
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * Set the parameters collection.
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = [];

        foreach ($params as $name => $value) {
            $this->setParam($name, $value);
        }
    }

    /**
     * Set a single parameter by $name.
     *
     * @param string $name   The name of the new parameter.
     * @param mixed  $value  The value of the parameter.
     */
    public function setParam(string $name, $value)
    {
        $this->params[$name] = $value;
    }
}