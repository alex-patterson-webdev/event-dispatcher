
# About

A simple implementation of the PSR-14 event dispatcher.

# Installation

Installation via [composer](https://getcomposer.org).

    require alex-patterson-webdev/event-manager ^1
    
# Usage

## The Event Manager

The `Arp\EventManager\EventManager` class is responsible for the event orchestration. You can create a new instance without and dependencies.

    $eventManager = new EventManager();
 
To execute event listeners, we ask the event manager to 'trigger' an event with a given name. Any event listeners that have been attached 
to the event manager with a matching name will be execute in priority order.

    $eventManager->trigger('foo'); // Trigger the event foo.
    
Alternatively, we can provide a already created event instance to the `triggerEvent()` method.
     
     $event = new Event('foo');
     $eventManager->triggerEvent($event);
     
### The Event object        

The event object represents the context of the triggered event; it can be any class that implements `Arp\EventManager\EventInterface`. When triggering events,
the event manager will pass the event instance to each of the event listeners that are executed.  
    
### Event Listeners

We can attach one or more listeners to the event manager using `EventManager::attachListener()` method. Event listeners can be any type of PHP `callable`
and optionally provide a 'priority' to the event manager to modify the order in which the listeners will be triggered. The event manager will trigger events 
from the highest to the lowest priority. Any event listeners that share priorities will be executed in the order that they were attached.

    $listener = function($event) {
        //... do something...
    };
    
    $eventManager->attachListener('foo.name', $listener);
    
The `EventSubscriberInterface` can be used if you have a number of listeners or wish to encapsulate the calls to `attachListener()` in one place.

    class MyEventSubscriber implements EventSubscriberInterface
    {
        public function subscribe(EventManagerInterface $eventManager)
        {
            $eventManager->attachListener('foo', [$this, 'doSomething'], 1);
            $eventManager->attachListener('bar', [$this, 'doSomethingOnBar], 999);
        }
        
        public function doSomething(EventInterface $event)
        {
            // executed when triggering 'foo'
        }
        
        public function doSomethingElse(EventInterface $event)
        {
            // executed when triggering 'bar'
        }
    }    