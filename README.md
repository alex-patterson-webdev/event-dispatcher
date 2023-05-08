![github workflow](https://github.com/alex-patterson-webdev/event-dispatcher/actions/workflows/workflow.yml/badge.svg)
[![codecov](https://codecov.io/gh/alex-patterson-webdev/event-dispatcher/branch/master/graph/badge.svg)](https://codecov.io/gh/alex-patterson-webdev/event-dispatcher)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-patterson-webdev/event-dispatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-patterson-webdev/event-dispatcher/?branch=master)

# Arp\EventDispatcher

## About

An implementation of the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/).

## Installation

Installation via [composer](https://getcomposer.org).

    require alex-patterson-webdev/event-dispatcher ^3

## Usage

### Dispatching events

The `Arp\EventDispatcher\EventDispatcher` class is responsible for executing the event listeners for any given event instance. 
The event dispatcher requires a `Arp\EventDispatcher\Listener\ListenerProvider` instance as a single dependency. 
The `ListenerProvider` contains all the event listeners that can be dispatched.

    use Arp\EventDispatcher\EventDispatcher;
    use Arp\EventDispatcher\Listener\ListenerProvider;

    $listenerProvider = new ListenerProvider();
    $dispatcher = new EventDispatcher($listenerProvider);
    
The call to `EventDispatcher::dispatch()` will loop through the registered event listeners from the provider. 
Any listeners that match `$event` will be executed using the configured priority order.

    $dispatcher->dispatch(new \My\Event\Foo());

Any object can be used as an event; by default an internal `EventNameResolver` instance will return the fully qualified class 
name of the object to use as the event name. See the 'Event Name Resolver' section for more configuration options.

### Event Listener Registration
 
The `Arp\EventDispatcher\Listener\ListenerProvider` is an implementation of the `Psr\EventDispatcher\ListenerProviderInterface`. 
The class has been designed to store and 'provide' an `iterable` collection of event listeners for a given event object.
The `ListenerProvider` also implements interface `Arp\EventDispatcher\Listener\AddListenerAwareInterface` which exposes public 
methods which allow for the registration of event listeners.
 
We can register any PHP `callable` data type directly using the `ListenerProvider::addListenerForEvent()` method. 

For example :
    
    use Arp\EventDispatcher\Listener\ListenerProvider;
    use My\Event\Foo;

    $listenerProvider = new ListenerProvider();

    $event = new Foo();
    $listener = static function (Foo $event) {
        echo 'The My\Event\Foo event was dispatched' . PHP_EOL;
    };
    
    $listenerProvider->addListenerForEvent($event, $listener);

We can also attach an array of listeners with the `addListenersForEvent()` method.
    
    $listeners = [
        new class() {
            public function __invoke(Foo $event): void {
                echo 'Listener 1' . PHP_EOL;
            }
        },
        static function (Foo $event) {
            echo 'Listener 2' . PHP_EOL;
        },
    ];
    
    $listenerProvider->addListenersForEvent($event, $listeners);
    
We are able to then retrieve these event listeners by calling `$listenerProvider->getListenersForEvent($event);`. The result is 
an instance of `Arp\EventDispatcher\Listener\ListenerCollectionInterface`, which contains the event listeners that were added.

    /** @var ListenerCollectionInterface $listenerCollection */
    $listenerCollection = $listenerProvider->getListenersForEvent($event);

### Adding Listeners via the Event Dispatcher

For convenience, the `EventDispatcher` class also implements `Arp\EventDispatcher\Listener\AddListenerAwareInterface`. 
This provides public methods to add event listeners to collections of the listener provider _after_ it has been passed to the `EventDispatcher`.

Internally calls to `addListenerForEvent()` and `addListenersForEvent()` will proxy to the internal listener provider.

    use Arp\EventDispatcher\EventDispatcher;
    use Arp\EventDispatcher\Listener\ListenerProvider;
    
    $dispatcher = new EventDispatcher(new ListenerProvider());
    
    $listener1 = static function (Foo $event) {
        echo 'Event Listener 1' . PHP_EOL;
    };
    $listener2 = static function (Foo $event) {
        echo 'Event Listener 2' . PHP_EOL;
    };
    
    $dispatcher->addListenerForEvent($event, $listener1);
    $dispatcher->addListenerForEvent($event, $listener2);
    
    $dispatcher->dispatch(new My\Event\Foo());
         
### Immutable Event Dispatcher

If you do not want the collection of event listeners to be modified after being passed to the `EventDispatcher` you can
use the provided `Arp\EventDispatcher\ImmutableEventDispatcher`. This event dispatcher implementation does not expose any methods
that can change the initial listener provider.

## Listener Priority
    
Internally the `ListenerProvider` will keep an iterable priority queue of all the listeners for each event you provide.

The `$priority` argument allows the ordering of each listener. Higher priority listeners will execute before lower priority listeners. 
If two or more listeners share the same priority, they will respect the order in which they were added.
    
    use My\Event\Foo;
    use Arp\EventDispatcher\Listener\ListenerProvider;

    $event = new Foo();
    
    $listener1 = static function (Foo $event) {
        echo 'Listener 1' . PHP_EOL;
    };
    $listener2 = static function (Foo $event) {
        echo 'Listener 2' . PHP_EOL;
    };
    
    $listenerProvider->addListenerForEvent($event, $listener1, -100);
    $listenerProvider->addListenerForEvent($event, $listener2, 100);
    
    $eventDispatcher->dispatch($event);
    
    // Listener 2
    // Listener 1

## The Event Name Resolver
 
By default, the `EventNameResolver` will use the fully qualified class name of the event object as the name of the event that will be dispatched. There may 
however be times when you would like to provide the name of the event as a property of the event object. This can be achieved
by implementing `Arp\EventDispacther\Resolver\EventNameAwareInterface` on your event class or using the default `Arp\EventDispacther\NamedEvent`.

When an object that implements `EventNameAwareInterface` is passed to the `EventNameResolver` the provider will return the value of `getEventName()`.

    use Arp\EventDispatcher\Event\NamedEvent;

    // Dispatch the 'foo' event
    $eventDispatcher->dispatch(new NamedEvent('foo'));
    
## Event Propagation

In accordance with the PSR, if provided with a `Psr\EventDispatcher\StoppableEventInterface` event instance the dispatcher will respect the 
 result of the call `isPropagationStopped()`. If set to `true` the event dispatcher will be prevented from executing any further listeners.

## Unit Tests

PHP Unit test using PHPUnit.

    php vendor/bin/phpunit
