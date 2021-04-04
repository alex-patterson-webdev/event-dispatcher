<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Listener;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Listener
 */
interface AddableListenerProviderInterface extends ListenerProviderInterface, AddListenerAwareInterface
{
}
