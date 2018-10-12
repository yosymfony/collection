<?php

/*
 * This file is part of the Collection project.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Collection\tests;

use PHPUnit\Framework\TestCase;
use Yosymfony\Collection\ReadOnlyCollection;

class ReadOnlyCollectionTest extends TestCase
{
    /**
     * @expectedException Yosymfony\Collection\Exception\AttemptOfModifyingReadOnlyCollectionException
     * @expectedExceptionMessage Attempt of modifying a read-only collection with the key "age".
     */
    public function testOffsetSetMustThrowAnExceptionWhenModifyAnItem() : void
    {
        $collection = new ReadOnlyCollection([
            'name' => 'Victor',
            'age' => 35,
        ]);

        $collection['age'] = 40;
    }

    /**
     * @expectedException Yosymfony\Collection\Exception\AttemptOfModifyingReadOnlyCollectionException
     * @expectedExceptionMessage Attempt of modifying a read-only collection with the key "age".
     */
    public function testOffsetUnsetMustThrowAnExceptionWhenModifyAnItem() : void
    {
        $collection = new ReadOnlyCollection([
            'name' => 'Victor',
            'age' => 35,
        ]);

        unset($collection['age']);
    }
}
