<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

trait ParametersAwareTrait
{
    protected ParametersInterface $params;

    public function setParameters(ParametersInterface $params): void
    {
        $this->params = $params;
    }

    public function getParameters(): ParametersInterface
    {
        return $this->params;
    }

    public function getParam(string $name, mixed $default = null): mixed
    {
        return $this->params->getParam($name, $default);
    }

    public function setParam(string $name, mixed $value): void
    {
        $this->params->setParam($name, $value);
    }
}
