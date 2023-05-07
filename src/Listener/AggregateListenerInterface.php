<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

interface AggregateListenerInterface
{
    public function addListeners(AddListenerAwareInterface $collection): void;
}
