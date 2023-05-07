<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

final class ImmutableParameters implements ParametersInterface
{
    public function __construct(private readonly ParametersInterface $parameters)
    {
    }

    public function count(): int
    {
        return $this->parameters->count();
    }

    public function isEmpty(): bool
    {
        return $this->parameters->isEmpty();
    }

    public function hasParam(string $name): bool
    {
        return $this->parameters->hasParam($name);
    }

    public function getParam(string $name, mixed $default = null): mixed
    {
        return $this->parameters->getParam($name, $default);
    }

    public function getParams(): array
    {
        return $this->parameters->getParams();
    }

    public function setParams(array $params): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    public function setParam(string $name, mixed $value): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    public function removeParams(array $params = []): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    public function removeParam(string $name): bool
    {
        // Ignore any updates to parameters to respect immutability
        return false;
    }

    public function getKeys(): array
    {
        return $this->parameters->getKeys();
    }

    public function getValues(): array
    {
        return $this->parameters->getValues();
    }

    public function getIterator(): \Traversable
    {
        return $this->parameters->getIterator();
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->parameters->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->parameters->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Ignore any updates to parameters to respect immutability
    }

    public function offsetUnset(mixed $offset): void
    {
        // Ignore any updates to parameters to respect immutability
    }
}
