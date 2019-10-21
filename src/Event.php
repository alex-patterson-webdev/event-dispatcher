<?php

namespace Arp\EventManager;

/**
 * Event
 *
 * @package Arp\EventDispatcher
 */
class Event implements EventInterface, StoppableEventInterface
{
    /**
     * $name
     *
     * @var string
     */
    protected $name;

    /**
     * $data
     *
     * @var array
     */
    protected $data = [];

    /**
     * $context
     *
     * @var mixed
     */
    protected $context;

    /**
     * $propagationStopped
     *
     * @var bool
     */
    protected $propagationStopped = false;

    /**
     * __construct
     *
     * @param string $name
     * @param array  $data
     * @param mixed  $context
     */
    public function __construct($name, array $data = [], $context = null)
    {
        $this->setName($name);
        $this->setData($data);
        $this->setContext($context);
    }

    /**
     * getName
     *
     * Return the name of the event.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setName
     *
     * Set the name of the event.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * hasData
     *
     * Check if a data value has been set with the provided $name.
     *
     * @param string $name  The name of the data key to fetch.
     *
     * @return bool
     */
    public function hasData($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * getData
     *
     * Return the data set with the provided $name. If no value can be found $default is returned.
     *
     * @param string $name      The name of the data key to return.
     * @param mixed  $default   The default fallback value if no value can be found.
     *
     * @return mixed
     */
    public function getData($name = null, $default = null)
    {
        if (null === $name) {
            return $this->data;
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }

    /**
     * setData
     *
     * Set the event data collection. This method will clear all previous data values.
     *
     * @param array $data  The collection of data to set.
     */
    public function setData(array $data)
    {
        $this->data = [];

        foreach($data as $name => $value) {
            $this->addData($name, $value);
        }
    }

    /**
     * addData
     *
     * Add a single data value with the provided $name.
     *
     * @param string $name   The name of the value to set.
     * @param mixed  $value  The data value.
     */
    public function addData($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * removeData
     *
     * Remove a data value matching the provided $name.
     *
     * @param string $name  The name of the value to remove.
     *
     * @return bool  If the value is found and removed.
     */
    public function removeData($name)
    {
        if (array_key_exists($name, $this->data)) {
            unset($this->data[$name]);
            return true;
        }
        return false;
    }

    /**
     * getContext
     *
     * Return the event context.
     *
     * @return mixed|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * setContext
     *
     * Contextual instance of where the event is triggered.
     *
     * @param mixed|null $context
     */
    public function setContext($context = null)
    {
        $this->context = $context;
    }

    /**
     * isPropagationStopped
     *
     * @return boolean
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

    /**
     * setPropagate
     *
     * Set the event propagation value.
     *
     * @param boolean $propagationStopped
     */
    public function setPropagationStopped($propagationStopped)
    {
        $this->propagationStopped = $propagationStopped ? true : false;
    }

}