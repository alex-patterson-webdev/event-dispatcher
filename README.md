[![Build Status](https://travis-ci.com/alex-patterson-webdev/event-dispatcher.svg?branch=master)](https://travis-ci.com/alex-patterson-webdev/event-dispatcher)
[![codecov](https://codecov.io/gh/alex-patterson-webdev/event-dispatcher/branch/master/graph/badge.svg)](https://codecov.io/gh/alex-patterson-webdev/event-dispatcher)

# ARP\EventDispatcher

## About

An implementation of the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/).

Three loosely coupled classes provide a powerful implementation of the PSR-14 Event Dispatcher.

- `Arp\EventDispatcher\EventDispatcher` passes the provided event object to each event listener that is registered for a given event.
- `Arp\Listener\ListenerProvider` acts as a store of event listener collections, grouped by their event name's.
- `Arp\Resolver\EventNameResolver` translates event objects passed to the event provider into a unique event name string.

## Installation

Installation via [composer](https://getcomposer.org).

    require alex-patterson-webdev/event-dispatcher ^1
        
## Usage

## The Event Dispatcher

The `Arp\EventDispatcher\EventDispatcher` class is responsible for the event orchestration. We will pass an event object to the `dispatch()` method
and the event dispatcher will work out the listeners that should be executed.

The event dispatcher can be created using various configuration options using the supplied `Arp\EventDispatcher\Factory\EventDispatcherFactory`.

    use Arp\EventDispatcher\Factory\EventDispatcherFactory;

    $config = [];
    $eventDispatcher = (new EventDispatcherFactory())->create($config);

We can then *dispatch* any event by simply passing an `object` to the `dispatch()` method.
    
    $eventDispatcher->dispatch(new \My\Event\Foo);

Any object can be used as an event; by default the internal `EventNameResolver` will return the fully qualified class name of the object as the event name, see 
the `EventNameResolver` section for more configuration options.

## The Listener Provider and Event Listeners
 
In order to actually be useful, we will need to register some event listeners with a class implementing `Psr\EventDispatcher\ListenerProviderInterface`. This
 module provides a default implementation of this interface, `Arp\EventDispatcher\Listener\ListenerProvider`.
 
We can register any PHP `callable` directly using the `ListenerProvider::addListenerForEvent()` method.
    
    use Arp\EventDispatcher\Factory\Provider\ListenerProviderFactory;
    use Arp\EventDispatcher\Factory\EventDispatcherFactory;

    $listenerProvider = (new ListenerProviderFactory())->create([]);
    $eventDispatcher = (new EventDispatcherFactory())->create([
        'listener_provider' => $listenerProvider,
    ]]);
    
    $event = new My\Event\Foo();
    
    $listenerProvider->addListenerForEvent($event, static function (Foo $event) {
        echo 'Event foo was dispatched';
    });
    
    $eventDispatcher->dispatch($event); // outputs 'Event foo was dispatched'

## Listener Priority
    
Internally the listener provider will keep an iterable priority queue of all the listeners for each event you provide.

The `$priority` argument allows the ordering of each listener. Higher priority listeners will execute before lower priority listeners. 
If two or more listeners share the same priority, they will respect the order in which they where added.
    
    use Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory;
    use Arp\EventDispatcher\Factory\EventDispatcherFactory;
    use Arp\EventDispatcher\Resolver\EventNameResolver;
    use Arp\EventDispatcher\EventDispatcher;
    
    $listenerProvider = (new ListenerProviderFactory())->create([]);
    $eventDispatcher = (new EventDispatcherFactory())->create([
        'listener_provider' => $listenerProvider,
    ]]);
    
    $listener1 = static function (Foo $event) {
        echo 'Listener 1' . PHP_EOL;
    };
    $listener2 = static function (Foo $event) {
        echo 'Listener 2';
    };
    
    $event = new My\Event\Foo();
    
    $listenerProvider->addListenerForEvent($event, $listener1, -100);
    $listenerProvider->addListenerForEvent($event, $listener2, 100);
    
    $eventDispatcher->dispatch($event);
    
    // Listener 2
    // Listener 1

## The Event Name Resolver
 
By default the `EventNameResolver` will use the fully qualified class name of the event object as the name of the event that will be dispatched. There may 
however be times where you would like to provide the name of the event as a property of the event object. This can be achieved
by implementing `Arp\EventDispacther\Resolver\EventNameAwareInterface` on your event class or using the default `Arp\EventDispacther\NamedEvent`.

When an object that implements `EventNameAwareInterface` is passed to the `EventNameResolver` the provider will return the value of `getEventName()`.

    $namedEvent = new Arp\EventDispatcher\Event\NamedEvent('foo');
    
    // Dispatch the 'foo' event
    $eventDispatcher->dispatcher($namedEvent);   

## Unit Tests

PHP Unit test using PHPUnit 8.

    php vendor/bin/phpunit