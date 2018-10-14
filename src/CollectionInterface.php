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

interface CollectionInterface extends EditableCollectionInterface, ReadableCollectionInterface
{
    /**
     * Returns a read-only collection.
     *
     * @return ReadableCollectionInterface
     */
    public function getReadOnlyCollection() : ReadableCollectionInterface;
}
