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

interface EditableCollectionInterface
{
    /**
     * Adds a new key to the collection.
     *
     * @param mixed $key Key or index.
     * @param mixed $value
     *
     * @return CollectionInterface The collection itself.
     *
     * @throws KeyAddedPreviouslyException If the key was added previously.
     */
    public function add($key, $value) : CollectionInterface;

    /**
     * Clears all elements in this collection.
     *
     * @return CollectionInterface The collection itself.
     */
    public function clear() : CollectionInterface;

    /**
     * Removes an item from the collection.
     *
     * @param mixed $key Key or index.
     *
     * @return CollectionInterface The collection itself.
     */
    public function remove($key) : CollectionInterface;

    /**
     * Sets the given key and value in the collection.
     *
     * @param mixed $key Key or index.
     * @param mixed $value
     *
     * @return CollectionInterface The collection itself.
     */
    public function set($key, $value) : CollectionInterface;

    /**
     * Return and removes the first item from the collection.
     *
     * @return mixed
     */
    public function shift();

    /**
     * Iterates over the collection and calls the given callback
     * with each item in the collection. The items in the collection
     * will be replaced by the values returned by the callback.
     *
     * @param callable $callback A callback with the following signature:
     * ```
     * function(mixed $item) : mixed
     * ```
     * @return CollectionInterface The collection itself.
     */
    public function transform(callable $callback) : CollectionInterface;
}
