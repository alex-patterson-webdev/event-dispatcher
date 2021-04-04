<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

use Exception;
use Traversable;

/**
 * A parameters collection that is closed for modifications
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
final class ImmutableParameters implements ParametersInterface
{
    /**
     * @var ParametersInterface
     */
    private ParametersInterface $parameters;

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->parameters->count();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->parameters->isEmpty();
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParam(string $name): bool
    {
        return $this->parameters->hasParam($name);
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return $this->parameters->getParam($name, $default);
    }

    /**
     * @return array<mixed>
     */
    public function getParams(): array
    {
        return $this->parameters->getParams();
    }

    /**
     * @param array<mixed> $params
     */
    public function setParams(array $params): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam(string $name, $value): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    /**
     * @param array<mixed> $params
     */
    public function removeParams(array $params = []): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function removeParam(string $name): bool
    {
        // Ignore any updates to parameters to respect immutability
        return false;
    }

    /**
     * @return array<int, mixed>
     */
    public function getKeys(): array
    {
        return $this->parameters->getKeys();
    }

    /**
     * @return array<mixed>
     */
    public function getValues(): array
    {
        return $this->parameters->getValues();
    }

    /**
     * @return Traversable<mixed>
     *
     * @throws Exception
     */
    public function getIterator(): \Traversable
    {
        return $this->parameters->getIterator();
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->parameters->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->parameters->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        // Ignore any updates to parameters to respect immutability
    }
}
