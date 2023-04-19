<?php declare(strict_types=1);

namespace Vouchers\Bag;

use Iterator;

class Collection implements Iterator
{
    /**
     * Holds all of our 'vouchers'.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Returns the current element.
     *
     * @return mixed
     */
    public function current() :mixed
    {
        return current($this->values);
    }

    /**
     * Returns the key of our current.
     */
    public function key() :mixed
    {
        return key($this->values);
    }

    /**
     * Moves array to next array item.
     */
    public function next() :void
    {
        return next($this->values);
    }

    /**
     * Reset the array.
     */
    public function rewind() :void
    {
        return reset($this->values);
    }

    /**
     * How many?
     *
     * @return int
     */
    public function count() :int
    {
        return count($this->values);
    }

    /**
     * Make sure the key is a real one
     * or loops will last forever.
     */
    public function valid() :bool
    {
        $key = key($this->values);

        return $key !== null && $key !== false;
    }

    /**
     * Returns a full array for codes.
     *
     * @return array
     */
    public function toArray() :array
    {
        $collection = [];
        foreach ($this->values as $value) {
            $collection[] = (string) $value;
        }

        return $collection;
    }
}
