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
    public function testOffsetSetMustThrowAnExceptionWhenModifyAnItem(): void
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
    public function testOffsetUnsetMustThrowAnExceptionWhenModifyAnItem(): void
    {
        $collection = new ReadOnlyCollection([
            'name' => 'Victor',
            'age' => 35,
        ]);

        unset($collection['age']);
    }

    public function testAllMustReturnTheUnderlyingArrayOfTheCollection(): void
    {
        $array = [
            'section' => 'config',
            'values' => $this->makeCollection(['port' => 443]),
        ];

        $collection = $this->makeCollection($array);

        $this->assertEquals($array, $collection->all());
    }

    public function testEveryMustReturnTrueIfAllItemPassTheTest(): void
    {
        $collection = $this->makeCollection([1, 2, 3]);

        $this->assertTrue($collection->every(function ($item) {
            return $item > 0;
        }));
    }

    public function testEveryMustReturnFalseIfAtLeastOneItemDoesNotPassTheTest(): void
    {
        $collection = $this->makeCollection([5, 4, 7]);

        $this->assertFalse($collection->every(function ($item) {
            return $item > 5;
        }));
    }

    public function testAnyMustReturnTrueIfAtLeastOneItemPassTheTest(): void
    {
        $collection = $this->makeCollection([5, 4, 7]);

        $this->assertTrue($collection->any(function ($item) {
            return $item > 5;
        }));
    }

    public function testAnyMustReturnFalseIfNoneItemPassTheTest(): void
    {
        $collection = $this->makeCollection([5, 4, 7]);

        $this->assertFalse($collection->any(function ($item) {
            return $item > 10;
        }));
    }

    public function testCountMustReturnTheNumberOfItemsInTheCollection(): void
    {
        $collection = $this->makeCollection([
            'name' => 'Yo! Symfony',
            'port' => 443,
        ]);

        $this->assertCount(2, $collection);
    }

    public function testExceptMustReturnTheCollectionExceptForItemsWithTheSpecifiedKeys(): void
    {
        $key = 'name1';
        $value = 'Yo! Symfony';
        $collection = $this->makeCollection([
            $key => $value,
            'name2' => 'Clint Eastwood',
        ]);

        $noName1Collection = $collection->except(['name2']);

        $this->assertCount(1, $noName1Collection);
        $this->assertEquals([
            $key => $value,
        ], $noName1Collection->all());
    }

    public function testFirstOrDefaultMustReturnTheFirstElementOfTheCollection(): void
    {
        $collection = $this->makeCollection([1, 2]);

        $this->assertEquals(1, $collection->firstOrDefault());
    }

    public function testFirstOrDefaultMustReturnTheDefaultValueWhenTheCollectionIsEmpty(): void
    {
        $collection = $this->makeCollection();

        $this->assertNull($collection->firstOrDefault());
    }

    public function testgetIteratorMustReturnTheIteratorOfTheCollection(): void
    {
        $expectedValue = 3;
        $currentValue = 0;
        $collection = $this->makeCollection([1, 2]);

        foreach ($collection as $value) {
            $currentValue += $value;
        }

        $this->assertEquals($expectedValue, $currentValue);
    }

    public function testGetMustReturnTheValueOfTheKey(): void
    {
        $key = 'name';
        $value = 'Yo! Symfony';
        $collection = $this->makeCollection([
            $key => $value,
        ]);

        $this->assertEquals($value, $collection->get($key));
    }

    public function testGetMustReturnTheValueAssociatedWithTheIndex(): void
    {
        $value = 'Yo! Symfony';
        $collection = $this->makeCollection([$value]);

        $this->assertEquals($value, $collection->get(0));
    }

    /**
     * @expectedException Yosymfony\Collection\Exception\KeyNotFoundException
     * @expectedExceptionMessage  The Key "fake-key" does not exist in the collection.
     */
    public function testGetMustThrowAnExceptionWhenKeyNotFound(): void
    {
        $collection = $this->makeCollection([]);

        $collection->get('fake-key');
    }

    public function testGetDotMustReturnTheValueAtTheEndOfTheDotPath()
    {
        $country = 'Spain';
        $collection = $this->makeCollection([
            'users' => [
                'victor' => $this->makeCollection([
                    'name' => 'Víctor',
                    'country' => $country,
                ]),
            ]
        ]);

        $this->assertEquals($country, $collection->getDot('users.victor.country'));
    }

    public function testGetDotMustReturnTheDefaultValueWhenASegmentOfDotPathDoesNotExist()
    {
        $collection = $this->makeCollection([
            'users' => [
                'victor' => $this->makeCollection([
                    'name' => 'Víctor',
                    'country' => 'Spain',
                ]),
            ]
        ]);

        $this->assertEquals('unknown', $collection->getDot('users.jack.country', 'unknown'));
    }

    public function testGetDotMustReturnTheDefaultValueWhenDotPathIsNull(): void
    {
        $collection = $this->makeCollection([
            'users' => [
                'victor' => $this->makeCollection([
                    'name' => 'Víctor',
                    'country' => 'Spain',
                ]),
            ]
        ]);

        $this->assertEquals('unknown', $collection->getDot('', 'unknown'));
    }

    public function testGetDotMustReturnTheValueOfAKeyWhenKeyMatchWithDotPath(): void
    {
        $country = 'UK';
        $collection = $this->makeCollection([
            'users' => [
                'victor' => $this->makeCollection([
                    'name' => 'Víctor',
                    'country' => 'Spain',
                ]),
            ],
            'users.jack.country' => $country,
        ]);

        $this->assertEquals($country, $collection->getDot('users.jack.country'));
    }

    public function testGetOnlyMustReturnACollectionWithTheSpecifiedKeys(): void
    {
        $collection = $this->makeCollection([
            'name' => 'Clinton Eastwood, Jr',
            'alias' => 'Clint Eastwood',
            'occupation' => 'actor, director, producer',
        ]);

        $dataCollection = $collection->getOnly(['name', 'occupation']);

        $this->assertCount(2, $dataCollection);
        $this->assertEquals([
            'name' => 'Clinton Eastwood, Jr',
            'occupation' => 'actor, director, producer',
        ], $dataCollection->all());
    }

    public function testGetOnlyMustReturnACollectionWithTheSpecifiedKeysThatExists(): void
    {
        $collection = $this->makeCollection([
            'name' => 'Clinton Eastwood, Jr',
            'alias' => 'Clint Eastwood',
            'occupation' => 'actor, director, producer',
        ]);

        $dataCollection = $collection->getOnly(['name', 'occupation', 'age']);

        $this->assertCount(2, $dataCollection);
        $this->assertEquals([
            'name' => 'Clinton Eastwood, Jr',
            'occupation' => 'actor, director, producer',
        ], $dataCollection->all());
    }

    public function testGetOrDefaultMustReturnTheValueOfTheKey(): void
    {
        $key = 'name';
        $value = 'Yo! Symfony';
        $collection = $this->makeCollection([
            $key => $value,
        ]);

        $this->assertEquals($value, $collection->getOrDefault($key));
    }

    public function testGetOrDefaultMustReturnTheDefaultValueWhenKeyDoesNotExist(): void
    {
        $defaultValue = 'Víctor';
        $collection = $this->makeCollection([
            'name' => 'Yo! Symfony',
        ]);

        $this->assertEquals($defaultValue, $collection->getOrDefault('name2', $defaultValue));
    }

    public function testHasMustReturnTrueWhenTheKeyExistsInTheCollection(): void
    {
        $collection = $this->makeCollection([
            'name' => 'Yo! Symfony',
        ]);

        $this->assertTrue($collection->has('name'));
    }

    public function testHasMustReturnFalseWhenTheKeyDoesNotExistsInTheCollection(): void
    {
        $collection = $this->makeCollection([
            'name' => 'Yo! Symfony',
        ]);

        $this->assertFalse($collection->has('email'));
    }

    public function testIntersectMustReturnTheCommonValuesBetweenTwoCollections(): void
    {
        $collectionOriginal = $this->makeCollection([0 => 'car', 1 => 'bike']);
        $collection = $this->makeCollection([0 => 'bike', 1 => 'scooter']);

        $intersectCollection = $collectionOriginal->intersect($collection);

        $this->assertEquals([1 => 'bike'], $intersectCollection->all());
    }

    public function testIntersectMustReturnTheCommonValuesBetweenACollectionAndAnArray(): void
    {
        $collectionOriginal = $this->makeCollection(['car', 'bike']);

        $intersectCollection = $collectionOriginal->intersect(['bike', 'scooter']);

        $this->assertEquals([1 => 'bike'], $intersectCollection->all());
    }

    public function testIsEmptyMustReturnTrueWhenTheCollectionHasZeroElements(): void
    {
        $collection = $this->makeCollection([]);

        $this->assertTrue($collection->isEmpty());
    }

    public function testIsEmptyMustReturnFalseWhenTheCollectionHasAtLeastOneElements(): void
    {
        $collection = $this->makeCollection([1]);

        $this->assertFalse($collection->isEmpty());
    }

    public function testKeysMustReturnANewCollectionWithTheKeysOfTheCollection(): void
    {
        $collection = $this->makeCollection(['a', 'b', 'c']);

        $this->assertEquals([0, 1, 2], $collection->keys()->all());
    }

    public function testLastOrDefaultMustReturnTheFirstElementOfTheCollection(): void
    {
        $collection = $this->makeCollection([1, 2]);

        $this->assertEquals(2, $collection->lastOrDefault());
    }

    public function testLastOrDefaultMustReturnTheDefaultValueWhenTheCollectionIsEmpty(): void
    {
        $collection = $this->makeCollection();

        $this->assertNull($collection->lastOrDefault());
    }

    public function testMapMustReturnANewCollectionApplaingTheCallbackToEachItem(): void
    {
        $collection = $this->makeCollection([1, 2, 3]);

        $collectionAfterMapping = $collection->map(function ($item) {
            return $item + 1;
        });

        $this->assertEquals([2, 3, 4], $collectionAfterMapping->all());
    }

    public function testReduceMustReturnASingleValue(): void
    {
        $collection = $this->makeCollection([1, 2, 3]);

        $reducedValue = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 0);

        $this->assertEquals(6, $reducedValue);
    }

    public function testReverseMustReturnANewcollectionWithReversedItems(): void
    {
        $collection = $this->makeCollection([
            'first' => 1,
            'second' => 2,
            'third' => 3,
        ]);

        $reversedCollection = $collection->reverse();

        $this->assertEquals([
            'third' => 3,
            'second' => 2,
            'first' => 1,
        ], $reversedCollection->all());
    }

    public function testToArrayMustReturTheCollectionAsPlainArray(): void
    {
        $array = [
            'name' => 'Yo! Symfony',
        ];

        $collection = $this->makeCollection($array);

        $this->assertEquals($array, $collection->toArray());
    }

    public function testToArrayMustReturTheCollectionAsPlainArrayWhenThereIsACollection(): void
    {
        $collection = $this->makeCollection([
            'section' => 'config',
            'values' => $this->makeCollection(['port' => 443]),
        ]);

        $this->assertEquals([
            'section' => 'config',
            'values' => [
                'port' => 443,
            ],
        ], $collection->toArray());
    }

    public function testToJsonMustReturnTheCollectionAsJsonString(): void
    {
        $array = [
            'name' => 'Yo! Symfony',
        ];

        $collection = $this->makeCollection($array);

        $this->assertEquals(json_encode($array), $collection->toJson());
    }

    public function testUnionMustReturnTheUnionValuesBetweenTwoCollections(): void
    {
        $collection = $this->makeCollection([0 => 'car', 1 => 'bike']);
        $secondCollection = $this->makeCollection([0 => 'bike', 2 => 'scooter']);

        $unitedCollection = $collection->union($secondCollection);

        $this->assertEquals([
            0 => 'car',
            1 => 'bike',
            2 => 'scooter',
        ], $unitedCollection->all());
    }

    public function testUnionMustReturnTheUnionValuesBetweenACollectionAndAnArray(): void
    {
        $collection = $this->makeCollection([0 => 'car', 1 => 'bike']);

        $unitedCollection = $collection->union([0 => 'bike', 2 => 'scooter']);

        $this->assertEquals([
            0 => 'car',
            1 => 'bike',
            2 => 'scooter',
        ], $unitedCollection->all());
    }

    public function testValuesMustReturnTheValuessOfTheCollection(): void
    {
        $values = ['a', 'b', 'c'];
        $collection = $this->makeCollection($values);

        $this->assertEquals($values, $collection->values()->all());
    }

    public function testWhereMustReturnANewCollectionFiltered(): void
    {
        $collection = $this->makeCollection(['a', 'b', 'c']);

        $collectionFiltered = $collection->where(function ($item) {
            return $item === 'a';
        });

        $this->assertEquals(['a'], $collectionFiltered->all());
    }

    public function testOffsetExistsMustReturnTrueWhenKeyExists()
    {
        $key = 'first';
        $collection = $this->makeCollection([$key => 1]);

        $this->assertTrue(isset($collection[$key]));
    }

    public function testOffsetExistsMustReturnFalseWhenKeyDoesNotExist()
    {
        $collection = $this->makeCollection(['first' => 1]);

        $this->assertFalse(isset($collection['second']));
    }

    public function testOffsetGetMustReturnTheValueAssociatedWithTheKey()
    {
        $key = 'first';
        $value = 1;
        $collection = $this->makeCollection([$key => $value]);

        $this->assertEquals($value, $collection[$key]);
    }

    private function makeCollection(array $values = []): ReadOnlyCollection
    {
        return new ReadOnlyCollection($values);
    }
}
