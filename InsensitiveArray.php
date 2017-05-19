<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/19/2017
 * Time: 6:10 PM
 */
class InsensitiveArray implements ArrayAccess
{

    private $container = array();
    private $keysMap   = array();

    public function __construct(Array $initial_array = array())
    {
        $this->container = $initial_array;

        $keys = array_keys($this->container);
        foreach ($keys as $key)
        {
            $this->addMappedKey($key);
        }
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->container[] = $value;
        }
        else
        {
            $this->container[$offset] = $value;
            $this->addMappedKey($offset);
        }
    }

    public function offsetExists($offset)
    {
        if (is_string($offset))
        {
            return isset($this->keysMap[strtolower($offset)]);
        }
        else
        {
            return isset($this->container[$offset]);
        }
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset))
        {
            unset($this->container[$this->getMappedKey($offset)]);
            if (is_string($offset))
            {
                unset($this->keysMap[strtolower($offset)]);
            }
        }
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ?
            $this->container[$this->getMappedKey($offset)] :
            null;
    }

    public function getInternalArray()
    {
        return $this->container;
    }

    private function addMappedKey($key)
    {
        if (is_string($key))
        {
            $this->keysMap[strtolower($key)] = $key;
        }
    }

    private function getMappedKey($key)
    {
        if (is_string($key))
        {
            return $this->keysMap[strtolower($key)];
        }
        else
        {
            return $key;
        }
    }
}