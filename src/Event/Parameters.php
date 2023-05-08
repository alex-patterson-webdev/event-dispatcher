<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

final class Parameters implements ParametersInterface
{
    public function __construct(private array $params = [])
    {
        $this->setParams($params);
    }

    public function count(): int
    {
        return count($this->params);
    }

    public function isEmpty(): bool
    {
        return empty($this->params);
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->removeParams();
        foreach ($params as $name => $value) {
            $this->setParam($name, $value);
        }
    }

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

    public function getValues(): array
    {
        return array_values($this->params);
    }

    public function offsetExists(mixed $offset): bool
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

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getParam($offset);
    }

    public function getParam(string $name, mixed $default = null): mixed
    {
        if ($this->hasParam($name)) {
            return $this->params[$name];
        }
        return $default;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setParam($offset, $value);
    }

    public function setParam(string $name, mixed $value): void
    {
        $this->params[$name] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->removeParam($offset);
    }

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
