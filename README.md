
# About

A simple implementation of the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/).

# Installation

Installation via [composer](https://getcomposer.org).

    require alex-patterson-webdev/event-dispatcher ^1
        
# Usage

This module conforms with the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/) specification.

## Event Dispatcher

The `Arp\EventDispatcher\EventDispatcher` class is responsible for the event orchestration. When creating a new event dispatcher
you must provide it with a listener provider implementing `Psr\EventDispatcher\ListenerProviderInterface`. This
 module provides a default implementation of this interface, `Arp\EventDispatcher\Listener\ListenerProvider`.

    use \Arp\EventDispatcher\EventDispatcher;
    use \Arp\EventDispatcher\Listener\ListenerProvider;

    $listenerProvider = new ListenerProvider();
    $eventDispatcher = new EventDispatcher($listenerProvider);
    
We can then *dispatch* the event

    $event = new \My\Event\Foo;

    $eventDispatcher->dispatch($event);    

Any object can be used as an event; by default the fully qualified class name of the object will be used as the event name.
There may however be times where you would like to provide the name of the event as a property of the event object. This can be achieved
by implementing `Arp\EventDispacther\Resolver\EventNameAwareInterface` or using the default `Arp\EventDispacther\NamedEvent`.

## Event Listeners
 
In order to actually be useful, you will need to register some events on your listener provider. 
We can register these directly using the `ListenerProvider::addListenerForEvent()` method.
    
    use \My\Event\Foo;
    use \Arp\EventDispatcher\Listener\ListenerProvider;

    $listenerProvider = new ListenerProvider();
    
    $event = Foo::class;

    $listener = function (Foo $event) {
        echo 'Event foo was dispatched';
    };
    
    $listenerProvider->addListenerForEvent($event, $listener);

## Listener Priority
    
Internally the listener provider will keep an iterable priority queue of all the listeners for each event you provide.

The `$priority` argument allow the ordering of each listener. Higher priority listeners will execute before lower priority listeners. 
If two or more listeners share the same priority, they will respect the order in which they where added.
    
    use \My\Event\Foo;
    use \Arp\EventDispatcher\Listener\ListenerProvider;
    use \Arp\EventDispatcher\EventDispatcher;
    
    $listenerProvider = new ListenerProvider();
    
    $event = new Foo();
    
    $listener1 = function(Foo $event) {
        echo 'Listener 1' . PHP_EOL;
    };
    
    $listener2 = function(Foo $event) {
        echo 'Listener 2' . PHP_EOL;
    };
    
    $listenerProvider->addListenerForEvent($event, $listener1, -100);
    $listenerProvider->addListenerForEvent($event, $listener2, 100);
    
    $eventDispatcher = new EventDispatcher($listenerProvider);
    
    $eventDispatcher->dispatch($event);

Will output
    
    Listener 2
    Listener 1
    
# Unit Tests

PHP Unit test using PHPUnit 8.

    php vendor/bin/phpunit