<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @extends \IteratorAggregate<mixed, mixed>
 * @extends \ArrayAccess<mixed, mixed>
 */
interface ParametersInterface extends \IteratorAggregate, \Countable, \ArrayAccess
{
    public function hasParam(string $name): bool;

    public function getParam(string $name, mixed $default = null): mixed;

    public function getParams(): array;

    public function setParams(array $params): void;

    public function setParam(string $name, mixed $value): void;

    public function removeParams(array $params = []): void;

    public function removeParam(string $name): bool;

    public function count(): int;

    public function isEmpty(): bool;

    public function getKeys(): array;

    public function getValues(): array;
}
