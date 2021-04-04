<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

/**
 * @extends \IteratorAggregate<mixed, mixed>
 * @extends \ArrayAccess<mixed, mixed>
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Event
 */
interface ParametersInterface extends \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParam(string $name): bool;

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null);

    /**
     * @return array<mixed>
     */
    public function getParams(): array;

    /**
     * @param array<mixed> $params
     */
    public function setParams(array $params): void;

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam(string $name, $value): void;

    /**
     * @param array<mixed> $params
     */
    public function removeParams(array $params = []): void;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function removeParam(string $name): bool;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return array<int, mixed>
     */
    public function getKeys(): array;

    /**
     * @return array<mixed>
     */
    public function getValues(): array;
}
