<?php declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
final class Parameters implements ParametersInterface
{
    /**
     * @var array $params
     */
    private $params = [];

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setParams($params);
    }

    /**
     * @return bool
     */
    public function hasParams(): bool
    {
        return ! empty($this->params);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParam(string $name) : bool
    {
        return isset($this->params[$name]);
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        if ($this->hasParam($name)) {
            return $this->params[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params) : void
    {
        $this->removeParams([]);

        foreach ($params as $name => $value) {
            $this->setParam($name, $value);
        }
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam(string $name, $value) : void
    {
        $this->params[$name] = $value;
    }

    /**
     * @param array $params
     */
    public function removeParams(array $params = []) : void
    {
        if (empty($params)) {
            $params = $this->getKeys();
        }

        foreach($params as $name) {
            $this->removeParam($name);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function removeParam(string $name) : bool
    {
        if ($this->hasParam($name)) {
            unset($this->params[$name]);

            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->params);
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return empty($this->params);
    }

    /**
     * @return array
     */
    public function getKeys() : array
    {
        return array_keys($this->params);
    }

    /**
     * @return array
     */
    public function getValues() : array
    {
        return array_values($this->params);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        return $this->hasParam($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getParam($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) : void
    {
        $this->setParam($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset) : void
    {
        $this->removeParam($offset);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->params);
    }
}
