[![Build Status](https://travis-ci.com/alex-patterson-webdev/event-dispatcher.svg?branch=master)](https://travis-ci.com/alex-patterson-webdev/event-dispatcher)

# About

An implementation of the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/).

The `Arp\EventDispatcher` module is composed of 3 main components. These components, when used together, compose a loosely coupled and 
powerful implementation of the PSR-14 Event Dispatcher.

- `Arp\EventDispatcher\EventDispatcher` passes the provided event object to each event listener that is registered for a given event.
- `Arp\Listener\ListenerProvider` acts as a store of event listener collections, grouped by their event name's.
- `Arp\Resolver\EventNameResolver` translates event objects passed to the event provider into a unique event name string.

# Installation

Installation via [composer](https://getcomposer.org).

    require alex-patterson-webdev/event-dispatcher ^1
        
# Usage

## The EventDispatcher

The `Arp\EventDispatcher\EventDispatcher` class is responsible for the event orchestration. When creating a new event dispatcher
you must provide it with a listener provider, which is any class implementing `Psr\EventDispatcher\ListenerProviderInterface`. This
 module provides a default implementation of this interface, `Arp\EventDispatcher\Listener\ListenerProvider`.
 
 The listener provider will hold a collection of event listeners for each event. It exposed a public API that allows the 
 registration and creation of these listener collections.
 
 The `ListenerProvider` also requires a `Arp\EventDispatcher\Resolver\EventNameResolver` instance, so it is able to 
 translate a event name from the dispatched event object.

    use Arp\EventDispatcher\EventDispatcher;
    use Arp\EventDispatcher\Listener\ListenerProvider;
    use Arp\EventDispatcher\Resolver\EventNameResolver;

    $listenerProvider = new ListenerProvider(new EventNameResolver());
    $eventDispatcher = new EventDispatcher($listenerProvider);
    
We can then *dispatch* any event by simply passing an `object` to dispatch.

    $event = new \My\Event\Foo;

    $eventDispatcher->dispatch($event);    

Any object can be used as an event; by default the internal `EventNameResolver` will return the fully qualified class name of the object as the event name, see 
the `EventNameResolver` section for more configuration options.

## Event Listeners
 
The previous examples outline the instantiation and dispatching of events using the `EventDispatcher`, however in order to actually be useful, we will need to 
register some event listeners with the `ListenerProvider`.
 
We can register any PHP `callable` directly using the `ListenerProvider::addListenerForEvent()` method.
    
    use Arp\EventDispatcher\Listener\ListenerProvider;
    use Arp\EventDispatcher\Resolver\EventNameResolver;
    use Arp\EventDispatcher\EventDispatcher;

    $listenerProvider = new ListenerProvider(new EventNameResolver());
    $eventDispatcher = new EventDispatcher($listenerProvider);
    
    $listenerProvider->addListenerForEvent($event, function (Foo $event) {
        echo 'Event foo was dispatched';
    });
    
    $eventDispatcher->dispatch(new \My\Event\Foo); // outputs 'Event foo was dispatched'

## Listener Priority
    
Internally the listener provider will keep an iterable priority queue of all the listeners for each event you provide.

The `$priority` argument allow the ordering of each listener. Higher priority listeners will execute before lower priority listeners. 
If two or more listeners share the same priority, they will respect the order in which they where added.
    
    use My\Event\Foo;
    use Arp\EventDispatcher\Listener\ListenerProvider;
    use Arp\EventDispatcher\Resolver\EventNameResolver;
    use Arp\EventDispatcher\EventDispatcher;
    
    $listenerProvider = new ListenerProvider(new EventNameResolver());
    $eventDispatcher = new EventDispatcher($listenerProvider);
    
    $listener1 = static function(Foo $event) {
        echo 'Listener 1' . PHP_EOL;
    };
    $listener2 = static function(Foo $event) {
        echo 'Listener 2' . PHP_EOL;
    };
    
    $listenerProvider->addListenerForEvent($event, $listener1, -100);
    $listenerProvider->addListenerForEvent($event, $listener2, 100);
    
    $eventDispatcher->dispatch(new Foo());
    
    // Listener 2
    // Listener 1

## The EventNameResolver
 
By default the `EventNameProvider` will use the fully qualified class name of the event object as the name of the event that will be dispatched. There may 
however be times where you would like to provide the name of the event as a property of the event object. This can be achieved
by implementing `Arp\EventDispacther\Resolver\EventNameAwareInterface` on your event class or using the default `Arp\EventDispacther\NamedEvent`.

When an object that implements `EventNameAwareInterface` is passed to the `EventNameProvider` the provider will return the value of `getEventName()`.

    // Dispatch the 'foo' event using a 'named event'
    $namedEvent = new Arp\EventDispatcher\Event\NamedEvent('foo');
    $eventDispatcher->dispatcher($namedEvent);   

## Factories

The module provides various factory classes to allow the creation of `ListenerProvider` and `EventDispatcher` objects based on provided configuration.

For example

    use Arp\EventDispatcher\Factory\EventDispatcherFactory;
    use Arp\EventDispatcher\Factory\Listener\ListenerProviderFactory;

    $listenerProvider = (new ListenerProviderFactory())->create([]);
    $eventDispatcher  = (new EventDispatcherFactory())->create(['listener_provider' => $listenerProvider]);

# Unit Tests

PHP Unit test using PHPUnit 8.

    php vendor/bin/phpunit