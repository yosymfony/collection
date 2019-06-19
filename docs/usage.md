# Collections usage

Collections make easy to work with array of items. They have 
[*fluent interface*](https://en.wikipedia.org/wiki/Fluent_interface) and most of their methods return an entirely
new collection instance. 

## Types
* [Mixed collection](#mixed-collection)
* [Read-only collection](#read-only-collection)

## Mixed collection

Represents a collection of a set of key and value pairs. Values can be mixed.

Interfaces implemented:
* [CollectionInterface](https://github.com/yosymfony/collection/blob/master/src/CollectionInterface.php)
* [ArrayAccess](http://php.net/manual/en/class.arrayaccess.php)

```php
use Yosymfony\Collection\MixedCollection;

$collection = new MixedCollection([
    'users' => [
        'victor' => [
            'name' => 'Víctor',
            'age' => 30,
        ],
    ],
]);

$name = $collection->getDot('users.victor.age');

// 30
```

You can use collections as if they were arrays:

```php
$collection = new MixedCollection([1,2,3]);

print $collection[0];

// 1

foreach($collection as $number) {
    print $number;
}

// 1
// 2
// 3
```

### Methods

#### `add`

Adds a new key to the collection.

```php
$collection = new MixedCollection(['user' => 'Victor']);
$collection->add('github-user', 'yosymfony')->all();

// ['user' => 'Victor', 'github-user' => 'yosymfony']
```

An exception `KeyAddedPreviouslyException` will be thrown if the key was added previously.

Related methods: [addValue](#addValue), [addRangeOfValues](#addRangeOfValues).

#### `addValue`

Adds a value to the collection.

```php
$collection = new MixedCollection(['a']);
$collection->addValue('b')->all();

// ['a', 'b']
```

Related methods: [add](#add), [addRangeOfValues](#addRangeOfValues).

#### `addRangeOfValues`

Adds a range of values to the collection.

```php
$collection = new MixedCollection(['a']);
$collection->addRangeOfValues(['b', 'c'])->all();

// ['a', 'b', 'c']
```

Related methods: [add](#add), [addValue](#addValue).

#### `all`

Returns all the items in the collection.

```php
$collection = new MixedCollection([1,2,3]);
$collection->all();

// [1,2,3]
```

#### `any`

Finds out if at least one item in the collection pass the given test expressed as a *callback*.

```php
$collection = new MixedCollection([1,2,3]);
$collection->any(function ($item) {
    return > 0;
});

// true
```

The *callback* function has the following signature: `function (mixed $item) : bool`

Related methods: [every](#every), [where](#where).

#### `clear`

Clears all elements in this collection.

```php
$collection = new MixedCollection([1,2,3]);
$collection->clear()->all();

// []
```

Related method: [remove](#remove).


#### `copy`

Creates a new collection instance. If an item is a Collection, its `copy` method will be invoked.

```php
$collection = new MixedCollection([1,2,3]);
$newCollection = $collection->copy();

// [1,2,3]
```

#### `every`

Finds out if all items pass the given test expressed as a *callback*.

```php
$collection = new MixedCollection([1,2,3]);
$collection->every(function ($item) {
    return > 2;
});

// false
```

The *callback* function has the following signature: `function (mixed $item) : bool`

Related methods: [any](#any), [where](#where).

#### `except`

Returns a collection with all items except for those with specified keys.

```php
$collection = new MixedCollection(['user' => 'victor', 'last_change' => '2018-10-14', 'role' => 'user']);
$filtered = $collection->except(['last_change', 'role']);
$fileted->all();

// ['user' => 'victor'] 
```

#### `firstOrDefault`

Returns the first element in the collection or the default value if the collection is empty.

```php
$collection = new MixedCollection(['user' => 'victor', 'role' => 'user']);
$value = $collection->firstOrDefault();

// 'victor'
```

The default value is `null`. You may optionally pass a default value as argument:

```php
$collection = new MixedCollection();
$value = $collection->firstOrDefault('default-value');

// 'default-value'
```

Related method: [lastOrDefault](#lastOrDefault).

#### `get`

Returns the value associated with the key.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$value = $collection->get('user');

// victor
```

If the key is not register in the collection a [`KeyNotFoundException`](https://github.com/yosymfony/collection/blob/master/src/Exception/KeyNotFoundException.php) will be thrown.

If you want to get a default value in case the key does not exists in the collection, use the method [`getOrDefault`](#getOrDefault).

Related methods: [getOnly](#getonly), [getOrDefault](#getordefault), [getDot](#getdot).

#### `getOnly`

Returns a new collection with the items associated with the specified keys.
If a key does not exists in the collection, it will be ignored.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony', 'country' => 'Spain']);
$values = $collection->getOnly(['user', 'country']);
$values->all();

// ['user' => 'victor', 'country' => 'Spain']
```

Related methods: [get](#get), [getOrDefault](#getordefault), [getDot](#getdot).

#### `getOrDefault`

Returns the value associated with the key or default value in case the key does not exists.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$value = $collection->getOrDefault('user');

// victor
```

You may optionally pass a default value as the second argument:

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$value = $collection->getOrDefault('foo', 'default-value');

// default-value
```

Related methods: [get](#get), [getOnly](#getonly), [getDot](#getdot).

#### `getDot`

Returns the value at the end of the path or the default value if an element of the path is missing.

```php
$collection = new MixedCollection([
    'users' => [
        'victor' => new MixedCollection([
            'name' => 'Víctor',
            'country' => 'Spain',
        ]),
        'alex' => [
            'name' => 'Alex',
            'country' => 'Spain',
        ],
    ],
]);

$victorCountry = $collection->getDot('users.victor.country'));
$alexName = $collection->getDot('users.alex.name'));

// Spain
// Alex
```

You may optionally pass a default value as the second argument:

```php
$collection = new MixedCollection([
    'users' => [
        'victor' => new MixedCollection([
            'name' => 'Víctor',
            'country' => 'Spain',
        ]),
    ]
]);

$value = $collection->getDot('users.jack.country', 'unknown-country'));

// unknown-country
```

Related methods: [get](#get), [getOnly](#getonly), [getOrDefault](#getordefault).

#### `getReadOnlyCollection`

Returns a [read-only collection](#read-only-collection).

```php
$collection = new MixedCollection([1,2,3]);
$readOnlyCollection = $collection->getReadOnlyCollection();
$readOnlyCollection->all();

// [1,2,3]
```

#### `has`

Determines if the item with the key exists in the collection.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$result = $collection->has('user');

// true
```

#### `isEmpty`

Returns true if the collection is empty.

```php
$collection = new MixedCollection();
$result = $collection->isEmpty();

// true
```

#### `intersect`

Returns the intersection of the collection with the given items or collection.
The resulting collection will preserve the original collection's keys:

```php
$collection = new MixedCollection(['car', 'bike', 'scooter']);
$intersect = $collection->intersect(['car', 'scooter']);
$intersect->all();

// [0 => 'car', 2 => 'scooter']
```

Related method: [union](#union).

#### `keys`

Returns a the keys of the collection items.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$keys = $collection->keys();
$keys->all();

// ['user', 'github-user']
```

Related method: [values](#values).

#### `lastOrDefault`

Returns the last element in the collection or default if the collection is empty.

```php
$collection = new MixedCollection(['user' => 'victor', 'role' => 'user']);
$value = $collection->lastOrDefault();

// 'user'
```

The default value is `null`. You may optionally pass a default value as argument:

```php
$collection = new MixedCollection();
$value = $collection->lastOrDefault('default-value');

// 'default-value'
```

Related method: [firtOrDefault](#firstOrDefault).

#### `map`
Iterates through the collection and passes each value to the given *callback*.
The **callback** function can return modify item.

```php
$collection = new MixedCollection([1,2,3]);
$values = $collection->map(function ($item) {
    return $item + 1;
});

// [2,3,4]
```

The *callback* function has the following signature: `function(mixed $item) : mixed`

Related method: [reduce](#reduce).

#### `reduce`

Reduces the collection to a single value. The initial value of `$carry` is null. However, you may
set its value by passing a second argument to `reduce`.

```php
$collection = new MixedCollection([1,2,3]);
$value = $collection->reduce(function ($carry, $item) {
    return $carry + $item;
}, 0);

// 6
```

The *callback* function has the following signature:
`function (mixed $carry, mixed $item, bool $isFirstItem, bool $isLastItem) : mixed`

`$isFirstItem` and `$isLastItem` let you know when you are handling the first and the last item of the collection.
For example, `$isLastItem` is useful in case of making a simplification to return the final result.

Related method: [map](#map).

#### `remove`

Removes an item from the collection.

```php
$collection = new MixedCollection(['user' => 'Victor', 'github-user' => 'yosymfony']);
$collection->remove('github-user');
$collection->all();

// ['user' => 'Victor']
```

Related methods: [clear](#clear), [except](#except).

#### `reverse`

Returns a new collection with reversed items.
This method preserves the original keys.

```php
$collection = new MixedCollection(['a', 'b', 'c']);
$reversed = $collection->reverse();

// [2 => 'c', 1 => 'b', 0 => 'a']
```

#### `shift`

Return and removes the first item from the collection.

```php
$collection = new MixedCollection([1,2,3]);
$value = $collection->shift();

// 1

$collection->all();

// [2,3]
```

#### `set`

Sets the given key and value in the collection.

```php
$collection = new MixedCollection(['user' => 'Victor']);
$collection->set('github-user', 'yosymfony');
$collection->all();

// ['user' => 'Victor', 'github-user' => 'yosymfony']
```


#### `transform`

Iterates over the collection and calls the given *callback*
with each item in the collection. The items in the collection
will be replaced by the values returned by the *callback*.

```php
$collection = new MixedCollection([1,2,3]);
$collection->transform(function ($item) {
    return $item + 1;
});
$collection->all();

// [2,3,4]
```

The *callback* function has the following signature: `function(mixed $item) : mixed`

#### `toArray`

Converts the collection into a plain array. 

```php
$collection = new MixedCollection(['a', 'b', 'c']);
$values = $collection->toArray();

// ['a', 'b', 'c']
```

If the collection contains another collections, they will be also converted to array:

```php
$collection = new MixedCollection([
    'a',
    'b',
    'c',
    new MixedCollection(['d', 'e']),
]);
$values = $collection->toArray();

// ['a', 'b', 'c', 'd', 'e']
```

#### `toJson`

Returns the collection of item as JSON.

```php
$collection = new MixedCollection(['name' => 'Victor']);
$value = $collection->toJson();

// '{"name":"Victor"}'
```

#### `union`
Returns the union of the collection with the given items or collection.
If the given items contains keys that are already in the original
collection, the original collection's values will be preferred.

```php
$collection = new MixedCollection([0 => 'car', 1 => 'bike', 2 => 'scooter']);
$union = $collection->union([0 =>'car', 1 => 'scooter']);
$union->all();

// [0 => 'car', 1 => 'bike' 2 => 'scooter']
```

Related method: [intersect](#intersect).

#### `values`

Returns the values of the collection.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$values = $collection->values();
$values->all();

// ['victor', 'yosymfony']
```

Related method: [keys](#keys).

#### `where`

Filter the collection using the given callback.
This method preserves the original keys.

```php
$collection = new MixedCollection(['user' => 'victor', 'github-user' => 'yosymfony']);
$filtered = $collection->where(function ($item, $key){
    return $key === 'user' && $item == 'victor';
});
$filtered->all();

// ['user' => 'victor']
```

The *callback* function has the following signature: `function (mixed $item, mixed $key) : bool`

Related methods: [any](#any), [every](#every).

## Read-only collection

Represents a read-only collection of elements. Once the elements have been set through the constructor, you
cannot add, delete or clear the collection. **This collection has the same method that `MixedCollection` but
those that came from [`EditableCollectionInterface`](https://github.com/yosymfony/collection/blob/master/src/EditableCollectionInterface.php): `add`, `clear`, `remove`, `set`,
`shift` and `transform`**. It neither has the method `getReadOnlyCollection`.

Interfaces implemented:
* [ReadableCollectionInterface](https://github.com/yosymfony/collection/blob/master/src/ReadableCollectionInterface.php)
* [ArrayAccess](http://php.net/manual/en/class.arrayaccess.php)

```php
use Yosymfony\Collection\MixedCollection;

$collection = new ReadOnlyCollection([1,2,3]);
$collection->all();

// [1,2,3]
```

If you try to modify this type of collection using the array style, an exception `AttemptOfModifyingReadOnlyCollectionException`
will be thrown.

```php
$collection = new ReadOnlyCollection(['user' => 'Víctor']);
$collection['user'] = 'Jack';

// AttemptOfModifyingReadOnlyCollectionException
```
