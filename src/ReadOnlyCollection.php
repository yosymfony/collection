<?php

/*
 * This file is part of the Collection project.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Collection;

use ArrayAccess;
use ArrayIterator;
use Yosymfony\Collection\Exception\AttemptOfModifyingReadOnlyCollectionException;

/**
 * Represents a read-only collection of elements.
 */
class ReadOnlyCollection implements ReadableCollectionInterface, ArrayAccess
{
    /** @var MixedCollection */
    private $collection;

    /**
     * Construct a read-only collection based on the array passed.
     *
     * @param array $items The items of the collection.
     */
    public function __construct(array $items)
    {
        $this->collection = new MixedCollection($items);
    }

    /**
     * {@inheritdoc}
     */
    public function all() : array
    {
        return $this->collection->all();
    }

    /**
     * {@inheritdoc}
     */
    public function any(callable $callback) : bool
    {
        return $this->collection->any($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function every(callable $callback) : bool
    {
        return $this->collection->every($callback);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->collection);
    }

    /**
     * {@inheritdoc}
     */
    public function except(array $keys) : CollectionInterface
    {
        return $this->collection->except($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrDefault($default = null)
    {
        return $this->collection->firstOrDefault($default);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getOnly(array $keys) : CollectionInterface
    {
        return $this->collection->getOnly($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrDefault($key, $default = null)
    {
        return $this->collection->getOrDefault($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getDot(string $dotPath, $default = null)
    {
        return $this->collection->getDot($dotPath, $default);
    }

    /**
    * Returns an iterator for the items.
    *
    * @return ArrayIterator
    */
    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->collection->all());
    }

    /**
     * {@inheritdoc}
     */
    public function has($key) : bool
    {
        return $this->collection->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() : bool
    {
        return $this->collection->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function intersect($items) : CollectionInterface
    {
        return $this->collection->intersect($items);
    }

    /**
     * {@inheritdoc}
     */
    public function keys() : CollectionInterface
    {
        return $this->collection->keys();
    }

    /**
     * {@inheritdoc}
     */
    public function lastOrDefault($default = null)
    {
        return $this->collection->lastOrDefault($default);
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback) : CollectionInterface
    {
        return $this->collection->map($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function reduce(callable $callback, $initial = null)
    {
        return $this->collection->reduce($callback, $initial);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse() : CollectionInterface
    {
        return $this->collection->reverse();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(int $options = 0) : string
    {
        return $this->collection->toJson($options);
    }

    /**
     * {@inheritdoc}
     */
    public function union($items) : CollectionInterface
    {
        return $this->collection->union($items);
    }

    /**
     * {@inheritdoc}
     */
    public function values() : CollectionInterface
    {
        return $this->collection->values();
    }

    /**
     * {@inheritdoc}
     */
    public function where(callable $callback) : CollectionInterface
    {
        return $this->collection->where($callback);
    }

    /**
     * Returns if exist an item at an offset.
     *
     * @see http://php.net/manual/en/class.arrayaccess.php
     *
     * @param  mixed  $key
     *
     * @return bool
     */
    public function offsetExists($key) : bool
    {
        return $this->has($key);
    }

    /**
     * Returns the item at a given offset.
     *
     * @see http://php.net/manual/en/class.arrayaccess.php
     *
     * @param  mixed  $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * A read-only collection cannot be modified.
     *
     * @see http://php.net/manual/en/class.arrayaccess.php
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return void
     *
     * @throws AttemptOfModifyingReadOnlyCollection
     */
    public function offsetSet($key, $value) : void
    {
        $this->exceptionAttemptOfModifyingReadOnlyCollection($key);
    }

    /**
     * A read-only collection cannot be modified.
     *
     * @see http://php.net/manual/en/class.arrayaccess.php
     *
     * @param  string  $key
     *
     * @return void
     *
     * @throws AttemptOfModifyingReadOnlyCollection
     */
    public function offsetUnset($key) : void
    {
        $this->exceptionAttemptOfModifyingReadOnlyCollection($key);
    }

    /**
     * Converts the collection to its string representation.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->collection->toJson();
    }

    private function exceptionAttemptOfModifyingReadOnlyCollection($key) : void
    {
        $message = sprintf('Attempt of modifying a read-only collection with the key "%s".', $key);
        
        throw new AttemptOfModifyingReadOnlyCollectionException($message, $key);
    }
}
