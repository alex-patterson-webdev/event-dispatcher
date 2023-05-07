<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

interface ParametersAwareInterface
{
    public function setParameters(ParametersInterface $params): void;

    public function getParameters(): ParametersInterface;

    public function getParam(string $name, mixed $default = null): mixed;

    public function setParam(string $name, mixed $value): void;
}
