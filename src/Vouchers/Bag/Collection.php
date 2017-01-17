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
        return reset($this->values);
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
     * Make sure the key is a real one
     * or loops will last forever.
     */
    public function valid()
    {
        $key = key($this->values);
        return ($key !== null && $key !== false);
    }

    /**
     * Returns a full array for codes.
     *
     * @return array
     */
    public function toArray()
    {
        $collection = [];
        foreach ($this->values as $value) {
            $collection[] = (string)$value;
        }

        return $collection;
    }
}
