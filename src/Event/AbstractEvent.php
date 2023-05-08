<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Event;

abstract class AbstractEvent implements ParametersAwareInterface
{
    use ParametersAwareTrait;

    /**
     * @param ParametersInterface<mixed>|iterable<mixed> $params
     */
    public function __construct(iterable $params = [])
    {
        if (!$params instanceof ParametersInterface) {
            $params = new Parameters(is_array($params) ? $params : []);
        }

        $this->setParameters($params);
    }
}
