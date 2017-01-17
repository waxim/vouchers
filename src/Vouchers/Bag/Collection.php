<?php

namespace Vouchers\Bag;

class Collection implements \Iterator
{
    /**
     * Holds all of our 'vouchers'
     *
     * @var array
     */
    protected $values = [];

    /**
     * Returns the current element
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->values);
    }

    /**
     * Returns the key of our current.
     */
    public function key()
    {
        return key($this->values);
    }

    /**
     * Moves array to next array item.
     */
    public function next()
    {
        return next($this->values);
    }

    /**
     * Reset the array
     */
    public function rewind()
    {
        return rewind($this->values);
    }

    /**
     * How many?
     *
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * Juat assume everything is valid.
     */
    public function valid()
    {
        return true;
    }

    public function toArray(array $fields = null)
    {
        if ($fields) {
            // do something
        }

        $collection = [];
        foreach ($this->values as $value) {
            $collection[] = (string)$value;
        }

        return $collection;
    }
}
