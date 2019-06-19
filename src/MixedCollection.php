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
use Yosymfony\Collection\Exception\KeyAddedPreviouslyException;
use Yosymfony\Collection\Exception\KeyNotFoundException;

/**
 * Represents a collection of a set of key and value pairs. Values can be mixed.
 */
class MixedCollection implements CollectionInterface, ArrayAccess
{
    /** @var array */
    protected $items = [];

    /**
     * Construct a collection based on the array passed.
     *
     * @param array $items The items of the collection.
     */
    public function __construct(iterable $items = [])
    {
        foreach ($items as $key => $value) {
            $this->add($key, $value);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function add($key, $value) : CollectionInterface
    {
        if ($this->has($key)) {
            throw new KeyAddedPreviouslyException("The key \"{$key}\" was added previously.", $key);
        }

        $this->set($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addValue($value): CollectionInterface
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRangeOfValues(iterable $values): CollectionInterface
    {
        foreach ($values as $value) {
            $this->addValue($value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function all() : array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function any(callable $callback) : bool
    {
        foreach ($this->items as $item) {
            if ($callback($item) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function copy() : CollectionInterface
    {
        $newCollection = $this->makeCollection([]);

        foreach ($this->items as $key => $value) {
            if ($value instanceof CollectionInterface) {
                $value = $value->copy();
            }

            $newCollection->add($key, $value);
        }

        return $newCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function every(callable $callback) : bool
    {
        foreach ($this->items as $item) {
            if ($callback($item) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear() : CollectionInterface
    {
        $this->items = [];

        return $this;
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function except(array $keys) : CollectionInterface
    {
        $collection = $this->makeCollection($this->items);

        foreach ($keys as $key) {
            $collection->remove($key);
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrDefault($default = null)
    {
        foreach ($this->items as $value) {
            return $value;
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (\key_exists($key, $this->items) === false) {
            throw new KeyNotFoundException("The Key \"{$key}\" does not exist in the collection.", $key);
        }

        return $this->items[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function getDot(string $dotPath, $default = null)
    {
        if ($dotPath === '') {
            return $default;
        }

        if ($this->has($dotPath)) {
            return $this->get($dotPath);
        }

        if (strpos($dotPath, '.') === false) {
            return $default;
        }

        $subPaths = explode('.', $dotPath);

        return $this->resolveDotPath($subPaths, $this, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getOnly(array $keys) : CollectionInterface
    {
        $collection = $this->makeCollection([]);

        foreach ($keys as $key) {
            if ($this->has($key)) {
                $collection->set($key, $this->get($key));
            }
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrDefault($key, $default = null)
    {
        if (\key_exists($key, $this->items) === false) {
            return $default;
        }

        return $this->items[$key];
    }

    /**
    * Returns an iterator for the items.
    *
    * @return ArrayIterator
    */
    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getReadOnlyCollection() : ReadableCollectionInterface
    {
        return new ReadOnlyCollection($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key) : bool
    {
        return \key_exists($key, $this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function intersect($items) : CollectionInterface
    {
        $itemsFromParameter = is_array($items) ? $items : $this->getArrayFromReadableCollection($items);

        return $this->makeCollection(array_intersect($this->items, $itemsFromParameter));
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() : bool
    {
        return $this->count() == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function keys() : CollectionInterface
    {
        return $this->makeCollection(array_keys($this->items));
    }

    /**
     * {@inheritdoc}
     */
    public function lastOrDefault($default = null)
    {
        return $this->reverse()->firstOrDefault($default);
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback) : CollectionInterface
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            $items[$key] = $callback($item);
        }

        return $this->makeCollection($items);
    }

    /**
     * {@inheritdoc}
     */
    public function reduce(callable $callback, $initial = null)
    {
        $carry = $initial;
        $isFirstItem = true;
        $numberOfItems = $this->count();
        $index = 0;

        foreach ($this->items as $item) {
            $isLastItem = ++$index === $numberOfItems;
            $carry = $callback($carry, $item, $isFirstItem, $isLastItem);
            $isFirstItem = false;
        }

        return $carry;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key) : CollectionInterface
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reverse() : CollectionInterface
    {
        return $this->makeCollection(\array_reverse($this->items, true));
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value) : CollectionInterface
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function shift()
    {
        return \array_shift($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return \array_map(function ($item) {
            return $item instanceof ReadableCollectionInterface ? $item->toArray() : $item;
        }, $this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(int $options = 0) : string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function transform(callable $callback) : CollectionInterface
    {
        foreach ($this->items as $key => $item) {
            $this->items[$key] = $callback($item);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function union($items) : CollectionInterface
    {
        $itemsFromParameter = is_array($items) ? $items : $this->getArrayFromReadableCollection($items);

        return $this->makeCollection($this->items + $itemsFromParameter);
    }

    /**
     * {@inheritdoc}
     */
    public function values() : CollectionInterface
    {
        return $this->makeCollection(array_values($this->items));
    }

    /**
     * {@inheritdoc}
     */
    public function where(callable $callback) : CollectionInterface
    {
        $arrayFiltered = \array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH);

        return $this->makeCollection($arrayFiltered);
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
     * Sets the item at a given offset.
     *
     * @see http://php.net/manual/en/class.arrayaccess.php
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($key, $value) : void
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->set($key, $value);
        }
    }

    /**
     * Unsets the item at a given offset.
     *
     * @see http://php.net/manual/en/class.arrayaccess.php
     *
     * @param  string  $key
     *
     * @return void
     */
    public function offsetUnset($key) : void
    {
        unset($this->items[$key]);
    }

    /**
     * Converts the collection to its string representation.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->toJson();
    }

    protected function makeCollection(array $items) : CollectionInterface
    {
        return new MixedCollection($items);
    }

    private function resolveDotPath(array $subPaths, ReadableCollectionInterface $collection, $default)
    {
        $subPath = array_shift($subPaths);

        if (count($subPaths) == 0) {
            return $collection->getOrDefault($subPath, $default);
        }

        $value = $collection->getOrDefault($subPath);

        if (!\is_array($value) && !$this->isReadableCollection($value)) {
            return $default;
        }

        if (\is_array($value) && !$this->isReadableCollection($value)) {
            $value = $this->makeCollection($value);
        }

        return $this->resolveDotPath($subPaths, $value, $default);
    }

    private function isReadableCollection($value) : bool
    {
        return $value instanceof ReadableCollectionInterface;
    }

    private function getArrayFromReadableCollection(ReadableCollectionInterface $collection) : array
    {
        return $collection->toArray();
    }
}
