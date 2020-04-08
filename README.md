[![Build Status](https://travis-ci.com/alex-patterson-webdev/event-dispatcher.svg?branch=master)](https://travis-ci.com/alex-patterson-webdev/event-dispatcher)
[![codecov](https://codecov.io/gh/alex-patterson-webdev/event-dispatcher/branch/master/graph/badge.svg)](https://codecov.io/gh/alex-patterson-webdev/event-dispatcher)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-patterson-webdev/event-dispatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-patterson-webdev/event-dispatcher/?branch=master)

# ARP\EventDispatcher

## About

An implementation of the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/).

## Installation

Installation via [composer](https://getcomposer.org).

    require alex-patterson-webdev/event-dispatcher ^1
        
## Usage

## The Listener Provider and Event Listener Registration
 
The `Arp\EventDispatcher\Listener\ListenerProvider` is an implementation of the `Psr\EventDispatcher\ListenerProviderInterface`. 
The class is designed to store and 'provide' an `iterable` collection of event listeners for a given event object.
 
We can register any PHP `callable` data type directly using the `ListenerProvider::addListenerForEvent()` method. 

For example :
    
    use My\Event\Foo;
    use Arp\EventDispatcher\Listener\ListenerProvider;

    $listenerProvider = new ListenerProvider();

    $event = new Foo();
    
    $listener = static function (Foo $event) {
        echo 'Event My\Event\Foo was dispatched' . PHP_EOL;
    };
    
    $listenerProvider->addListenerForEvent($event, $listener);

We can also attach an array of listeners with the `addListenersForEvent()` method.
    
    $listeners = [
        static function (Foo $event) {
            echo 'Listener 1' . PHP_EOL;
        },
        static function (Foo $event) {
            echo 'Listener 1' . PHP_EOL;
        },
    ];
    
    $listenerProvider->addListenersForEvent($event, $listeners);

## The Event Dispatcher

The `Arp\EventDispatcher\EventDispatcher` class is responsible for the execution of the required event listeners and it does so by using an internal `ListenerProvider`. 

When calling `$dispatcher->dispatch($event)` each event listener that is registered will for that event will be be executed in the configured order.

    use Arp\EventDispatcher\EventDispatcher;

    $dispatcher = new EventDispatcher($listenerProvider);
    
    $dispatcher->dispatch($event);

Any object can be used as an event; by default an internal `EventNameResolver` instance will return the fully qualified class name of the object to use as the event name. See 
the 'The Event Name Resolver' section for more configuration options.

## Listener Priority
    
Internally the `ListenerProvider` will keep an iterable priority queue of all the listeners for each event you provide.

The `$priority` argument allows the ordering of each listener. Higher priority listeners will execute before lower priority listeners. 
If two or more listeners share the same priority, they will respect the order in which they where added.
    
    use My\Event\Foo;
    use Arp\EventDispatcher\Listener\ListenerProvider;

    $event = new Foo();
    
    $listener1 = static function (Foo $event) {
        echo 'Listener 1' . PHP_EOL;
    };
    $listener2 = static function (Foo $event) {
        echo 'Listener 2';
    };
    
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

    use Arp\EventDispatcher\Event\NamedEvent;

    // Dispatch the 'foo' event
    $eventDispatcher->dispatch(new NamedEvent('foo'));
    
## Event Propagation

In accordance with the PSR, if provided with a `Psr\EventDispatcher\StoppableEventInterface` event instance the dispatcher will respect the 
 result of the call `isPropagationStopped()`. If set to `true` the event dispatcher will be prevented from executing any further listeners.

## Unit Tests

PHP Unit test using PHPUnit 8.

    php vendor/bin/phpunit
