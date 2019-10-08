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

/**
 * Interface for collections
 *
 * @author Víctor Puertas <vpgugr@gmail.com>
 */
interface CollectionInterface extends Countable, IteratorAggregate
{
    /**
     * Adds a new key to the collection
     *
     * @param string|int $key Key/index
     * @param mixed $value
     *
     * @return CollectionInterface The collection itself
     *
     * @throws KeyAddedPreviouslyException If the key was added previously
     */
    public function add($key, $value): self;

    /**
     * Adds a range of values to the collection
     *
     * @param iterable $values
     *
     * @return self The collection itself
     */
    public function addRangeOfValues(iterable $values): self;
    
    /**
     * Adds a value to the collection.
     *
     * @param mixed $value
     *
     * @return CollectionInterface The collection itself
     */
    public function addValue($value): self;

    /**
     * Returns all the items in the collection
     *
     * @return array
     */
    public function all(): array;

    /**
     * Finds out if at least one item in the collection pass the given test expressed as a callback
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $item) : bool
     * ```
     *
     * @return bool
     */
    public function any(callable $callback): bool;

    /**
     * Clears all elements in this collection
     *
     * @return self The collection itself
     */
    public function clear(): self;

    /**
     * Creates a copy of the collection
     *
     * @return self
     */
    public function copy(): self;

    /**
     * Finds out if all items pass the given test expressed as a callback
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $item) : bool
     * ```
     *
     * @return bool
     */
    public function every(callable $callback): bool;

    /**
     * Returns a collection with all items except for those with specified keys
     *
     * @param array $keys List of keys
     *
     * @return self A new collection
     */
    public function except(array $keys): self;

    /**
     * Returns the first element in the collection or default if the collection is empty
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function first($default = null);

    /**
     * Returns the value associated with the key
     *
     * @param string|int $key Key/index
     *
     * @return mixed
     *
     * @throws KeyNotFoundException
     */
    public function get($key);

    /**
     * Returns the items associated with the specified keys.
     * If a key does not exists in the collection, it will be ignored
     *
     * @param array $keys List of keys
     *
     * @return self A new collection
     */
    public function getOnly(array $keys) : self;

    /**
     * Returns the value associated with the key or default value in case the key does not exists
     *
     * @param string|int $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOrDefault($key, $default = null);

    /**
     * Returns the value at the end of the path or default if an element of the path
     * is missing
     *
     * @param string $dotPath Example: "users.victor.country"
     * @param mixed $default
     *
     * @return mixed
     */
    public function getDot(string $dotPath, $default = null);

    /**
     * Determines if the item with the key exists in the collection
     *
     * @param string|int $key Key/index
     *
     * @return bool
     */
    public function has($key): bool;

    /**
     * Returns true if the collection is empty
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Returns the intersection of the collection with the given items or collection.
     * The resulting collection will preserve the original collection's keys
     *
     * @param iterable $items
     *
     * @return self A new collection with the values of the intersection
     */
    public function intersect(iterable $items): self;

    /**
     * Returns the keys of the collection items
     *
     * @return self A new collection
     */
    public function keys(): self;

    /**
     * Returns the last element in the collection or default if the collection is empty
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function last($default = null);

    /**
     * Iterates through the collection and passes each value to the given callback.
     * The callback function can return modify item
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function(mixed $item) : mixed
     * ```
     *
     * @return self A new collection containing all the elements of the collection after
     * applying the callback function to each one
     */
    public function map(callable $callback): self;

    /**
     * Reduces the collection to a single value
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
     * Removes an item from the collection
     *
     * @param string|int $key Key/index
     *
     * @return self The collection itself
     */
    public function remove($key): self;

    /**
     * Returns a new collection with reversed items
     *
     * @return self A new collection
     */
    public function reverse(): self;

    /**
     * Sets the given key and value in the collection
     *
     * @param mixed $key Key/index
     * @param mixed $value
     *
     * @return self The collection itself
     */
    public function set($key, $value): self;

    /**
     * Return and removes the first item from the collection
     *
     * @return mixed
     */
    public function shift();

    /**
     * Iterates over the collection and calls the given callback
     * with each item in the collection. The items in the collection
     * will be replaced by the values returned by the callback
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function(mixed $item) : mixed
     * ```
     * @return self The collection itself
     */
    public function transform(callable $callback): self;

    /**
     * Converts the collection into a plain array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Returns the collection of item as JSON.
     *
     * @param int $options Bitmask that set the serialization behavior.
     * {@see http://www.php.net/manual/en/function.json-encode.php}
     *
     * @return string
     */
    public function toJson(int $options = 0): string;

    /**
     * Returns the union of the collection with the given items or collection.
     * If the given items contains keys that are already in the original
     * collection, the original collection's values will be preferred
     *
     * @param iterable $items
     *
     * @return self A new collection with the union values
     */
    public function union(iterable $items): self;

    /**
     * Returns the values of the collection
     *
     * @return self A new collection
     */
    public function values(): self;

    /**
     * Filter the collection using the given callback.
     * This method preserves the original keys
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function (mixed $item, mixed $key) : bool
     * ```
     *
     * @return self A new collection
     */
    public function where(callable $callback): self;
}
