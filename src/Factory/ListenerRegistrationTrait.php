<?php

declare(strict_types=1);

namespace Arp\EventDispatcher\Factory;

use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\AggregateListenerInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Arp\Factory\Exception\FactoryException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\EventDispatcher\Factory
 */
trait ListenerRegistrationTrait
{
    /**
     * Register a collection of event listeners with the provided listener $collection.
     *
     * $listenerConfig can be provided in the following format.
     *
     * $listenerConfig = [
     *      new class implements AggregateListenerInterface {
     *          //...
     *      }
     *
     *      or...
     *
     *      'eventA' => [
     *          static function() {
     *              echo 'foo';
     *          },
     *      ],
     *      'eventB' => [
     *          static function() {
     *              echo 'bar';
     *          ),
     *          static function() {
     *              echo 'baz';
     *          },
     *      ],
     * ];
     *
     * @param AddListenerAwareInterface $collection      The collection that listeners are registered.
     * @param array                     $listenerConfig  The array configuration options of the listeners.
     *
     * @throws FactoryException
     */
    protected function registerEventListeners(AddListenerAwareInterface $collection, array $listenerConfig): void
    {
        foreach ($listenerConfig as $eventName => $listeners) {
            if ($listeners instanceof AggregateListenerInterface) {
                $listeners->addListeners($collection);
                continue;
            }

            if (!is_iterable($listeners)) {
                throw new FactoryException(
                    sprintf(
                        'Event listeners must be of type \'%s\' or \'iterable\'; \'%s\' provided for event \'%s\'',
                        AggregateListenerInterface::class,
                        gettype($listeners),
                        $eventName
                    )
                );
            }

            foreach ($listeners as $listener) {
                $event = $eventName;
                $priority = 1;

                if (is_array($listener)) {
                    $priority = $listener['priority'] ?? $priority;
                    $event = $listener['event'] ?? '';
                    $listener = $listener['listener'] ?? null;
                }

                if (empty($event)) {
                    throw new FactoryException('Unable to register event listener with empty event name');
                }

                if (!is_callable($listener)) {
                    throw new FactoryException(
                        sprintf(
                            'Event listeners must be of type \'callable\'; '
                            . '\'%s\' provided for event \'%s\' at priority \'%d\'',
                            gettype($listener),
                            $event,
                            $priority
                        )
                    );
                }

                try {
                    $collection->addListenerForEvent($event, $listener, $priority);
                } catch (EventListenerException $e) {
                    throw new FactoryException(
                        sprintf(
                            'Failed to add new event listener for event \'%s\': %s',
                            $eventName,
                            $e->getMessage()
                        ),
                        $e->getCode(),
                        $e
                    );
                }
            }
        }
    }
}
