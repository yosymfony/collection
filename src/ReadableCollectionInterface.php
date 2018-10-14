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

use Countable;
use IteratorAggregate;

interface ReadableCollectionInterface extends Countable, IteratorAggregate
{
    /**
     * Returns all the items in the collection.
     *
     * @return array
     */
    public function all() : array;

    /**
     * Finds out if at least one item in the collection pass the given test expressed as a callback.
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $item) : bool
     * ```
     *
     * @return bool
     */
    public function any(callable $callback) : bool;

    /**
     * Finds out if all items pass the given test expressed as a callback.
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $item) : bool
     * ```
     *
     * @return bool
     */
    public function every(callable $callback) : bool;

    /**
     * Returns a collection with all items except for those with specified keys.
     *
     * @param array $keys List of keys.
     *
     * @return CollectionInterface A new collection.
     */
    public function except(array $keys) : CollectionInterface;

    /**
     * Returns the first element in the collection or default if the collection is empty.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function firstOrDefault($default = null);

    /**
     * Returns the value associated with the key.
     *
     * @param mixed $key
     *
     * @return mixed
     *
     * @throws KeyNotFoundException
     */
    public function get($key);

    /**
     * Returns the items associated with the specified keys.
     * If a key does not exists in the collection, it will be ignored.
     *
     * @param array $keys List of keys.
     *
     * @return CollectionInterface A new collection.
     */
    public function getOnly(array $keys) : CollectionInterface;

    /**
     * Returns the value associated with the key or default value in case the key does not exists.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOrDefault($key, $default = null);

    /**
     * Returns the value at the end of the path or default if an element of the path
     * is missing.
     *
     * @param string $dotPath Example: "users.victor.country"
     * @param mixed $default
     *
     * @return mixed
     */
    public function getDot(string $dotPath, $default = null);

    /**
     * Determines if the item with the key exists in the collection.
     *
     * @param mixed $key Key or index.
     *
     * @return bool
     */
    public function has($key) : bool;

    /**
     * Returns true if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty() : bool;

    /**
     * Returns the intersection of the collection with the given items or collection.
     * The resulting collection will preserve the original collection's keys.
     *
     * @param ReadableCollectionInterface|array $items
     *
     * @return CollectionInterface A new collection with the values of the intersection.
     */
    public function intersect($items) : CollectionInterface;

    /**
     * Returns the keys of the collection items.
     *
     * @return CollectionInterface A new collection.
     */
    public function keys() : CollectionInterface;

    /**
     * Returns the last element in the collection or default if the collection is empty.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function lastOrDefault($default = null);

    /**
     * Iterates through the collection and passes each value to the given callback.
     * The callback function can return modify item.
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function(mixed $item) : mixed
     * ```
     *
     * @return CollectionInterface A new collection containing all the elements of the collection after
     * applying the callback function to each one.
     */
    public function map(callable $callback) : CollectionInterface;

    /**
     * Reduces the collection to a single value.
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $carry, mixed $item, bool $isFirstItem, bool $isLastItem) : mixed
     * ```
     * @param mixed $initial The value of $carry on the first iteration
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null);

    /**
     * Returns a new collection with reversed items.
     *
     * @return CollectionInterface A new collection.
     */
    public function reverse() : CollectionInterface;

    /**
     * Converts the collection into a plain array.
     *
     * @return array
     */
    public function toArray() : array;

    /**
     * Returns the collection of item as JSON.
     *
     * @param int $options Bitmask that set the serialization behavior.
     * {@see http://www.php.net/manual/en/function.json-encode.php}
     *
     * @return string
     */
    public function toJson(int $options = 0) : string;

    /**
     * Returns the union of the collection with the given items or collection.
     * If the given items contains keys that are already in the original
     * collection, the original collection's values will be preferred.
     *
     * @param ReadableCollectionInterface|array $items
     *
     * @return CollectionInterface A new collection with the union values.
     */
    public function union($items) : CollectionInterface;

    /**
     * Returns the values of the collection.
     *
     * @return CollectionInterface A new collection.
     */
    public function values() : CollectionInterface;

    /**
     * Filter the collection using the given callback.
     * This method preserves the original keys.
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $item, mixed $key) : bool
     * ```
     *
     * @return CollectionInterface A new collection.
     */
    public function where(callable $callback) : CollectionInterface;
}
