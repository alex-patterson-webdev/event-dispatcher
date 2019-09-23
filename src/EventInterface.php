<?php

namespace Arp\EventManager;

/**
 * EventInterface
 *
 * @package Arp\EventManager
 */
interface EventInterface
{
    /**
     * getName
     *
     * Return the name of the event.
     *
     * @return mixed
     */
    public function getName();

    /**
     * setName
     *
     * Set the name of the event.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * hasData
     *
     * Check if a data value has been set with the provided $name.
     *
     * @param string $name  The name of the data key to fetch.
     *
     * @return bool
     */
    public function hasData($name);

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
    public function getData($name = null, $default = null);

    /**
     * setData
     *
     * Set the event data collection. This method will clear all previous data values.
     *
     * @param array $data  The collection of data to set.
     */
    public function setData(array $data);

    /**
     * addData
     *
     * Add a single data value with the provided $name.
     *
     * @param string $name   The name of the value to set.
     * @param mixed  $value  The data value.
     */
    public function addData($name, $value);

    /**
     * removeData
     *
     * Remove a data value matching the provided $name.
     *
     * @param string $name  The name of the value to remove.
     *
     * @return bool  If the value is found and removed.
     */
    public function removeData($name);

    /**
     * getContext
     *
     * Return the event context.
     *
     * @return mixed|null
     */
    public function getContext();

    /**
     * setContext
     *
     * Contextual instance of where the event is triggered.
     *
     * @param mixed $context
     */
    public function setContext($context = null);

    /**
     * propagate
     *
     * Check if the event should continue to propagate.
     *
     * @return boolean
     */
    public function propagate();

    /**
     * setPropagate
     *
     * Set the event propagation value.
     *
     * @param boolean $propagate
     */
    public function setPropagate($propagate);

}