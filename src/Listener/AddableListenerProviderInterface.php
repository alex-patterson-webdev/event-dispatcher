<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Psr\EventDispatcher\ListenerProviderInterface;

interface AddableListenerProviderInterface extends ListenerProviderInterface, AddListenerAwareInterface
{
}
