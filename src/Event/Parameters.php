<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
final class Parameters implements ParametersInterface
{
    /**
     * @var array<mixed> $params
     */
    private array $params = [];

    /**
     * @param array<mixed> $params
     */
    public function __construct(array $params = [])
    {
        $this->setParams($params);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->params);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->params);
    }

    /**
     * @return array<mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array<mixed> $params
     */
    public function setParams(array $params): void
    {
        $this->removeParams();

        foreach ($params as $name => $value) {
            $this->setParam($name, $value);
        }
    }

    /**
     * @param array<mixed> $params
     */
    public function removeParams(array $params = []): void
    {
        if (empty($params)) {
            $params = $this->getKeys();
        }

        foreach ($params as $name) {
            $this->removeParam($name);
        }
    }

    /**
     * @return array<int, mixed>
     */
    public function getKeys(): array
    {
        return array_keys($this->params);
    }

    /**
     * @return array<mixed>
     */
    public function getValues(): array
    {
        return array_values($this->params);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->hasParam($offset);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParam(string $name): bool
    {
        return array_key_exists($name, $this->params);
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
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->setParam($offset, $value);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam(string $name, $value): void
    {
        $this->params[$name] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->removeParam($offset);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function removeParam(string $name): bool
    {
        if ($this->hasParam($name)) {
            unset($this->params[$name]);
            return true;
        }
        return false;
    }

    /**
     * @return \ArrayIterator<int|string, mixed>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->params);
    }
}
