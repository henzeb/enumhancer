# Bitmasks

````php
enum Permission {
    use Henzeb\Enumhancer\Concerns\Bitmasks;

    case Create;
    case Read;
    case Update;
    case Delete;
}

Permission::Create->bit(); // returns 1
Permission::Read->bit(); // returns 2
Permission::Update->bit(); // returns 4
Permission::Delete->bit(); // returns 8
````

## Integer backed enums

Enumhancer allows you to use your own bit values by using an integer or
string backed enum. This is disabled by default. You can enable it by
adding a constant called `BIT_VALUES` that returns true.

````php
enum PermissionInt: int
{
    use Henzeb\Enumhancer\Concerns\Bitmasks;

    private const BIT_VALUES = true;

    case Create = 8;
    case Read = 16;
    case Update = 32;
    case Delete = 128;
}

PermissionInt::Create->bit(); // returns 8
PermissionInt::Read->bit(); // returns 16
PermissionInt::Update->bit(); // returns 32
PermissionInt::Delete->bit(); // returns 128
````

Note: when the flag `BIT_VALUES` is set, each case must have a valid bit set.
For example: `7` would throw a fatal error as this consists of the bits `1` `2`
and `4`.

### Modifiers

Sometimes, you want to use your enum as a modifier where
a combination might result in another case. This is not
possible by default, so you need to enable the
`BIT_MODIFIER` flag

````php
enum Permission: int
{
    use Henzeb\Enumhancer\Concerns\Bitmasks;

    private const BIT_VALUES = true;
    
    private const BIT_MODIFIER = true;

    case Nothing = 0;
    case Read = 1;
    case Write = 2;
    case ReadAndWrite = 3;
}

Permission::mask(
    Permission::Read, 
    Permission::Write
)->cases(); // returns [Permission::ReadWrite] 

````

## Bits

When you want to use the bits in a dropdown, you can easily use `bits`.

````php
// returns [1 => 'Create', 2 => 'Read', 4 => 'Update', 8 => 'Delete']
Permission::bits();

// returns [8 => 'Create', 16 => 'Read', 32 => 'Update', 128 => 'Delete']
PermissionInt::bits();

````

Note: Just like [Dropdown](dropdown.md), `bits` uses [Labels](labels.md) where applicable.

## Create bitmasks

Enumhancer gives you easy tools to work with bitmasks. Just like everywhere else,
if [Mappers](mappers.md) are being used, any value will be mapped.

### mask

To get a mask, simply call the static method on your enum. just like with
[Comparison](comparison.md), you can add as many enum or values that represent enums
as you need.

````php
Permission::mask(); // returns a Bitmask object with value 0
Permission::mask(Permission::Create); // returns Bitmask with value 1
Permission::mask(Permission::Create, 'update'); // returns Bitmask with value 5
Permission::mask(Permission::Create, 'modify'); // throws error
````

### fromMask

To transform a numeric bitmask, retreived from for example a database,
into a `Bitmask` object, you can use `fromMask`.

````php
Permission::fromMask(0); // returns a Bitmask object with value 0
Permission::fromMask(1); // returns Bitmask with value 1
Permission::fromMask(5); // returns Bitmask with value 5
Permission::fromMask(16); // throws error

PermissionInt::fromMask(32); // returns a Bitmask object with value 32
PermissionInt::fromMask(64); // throws error
````

numeric bitmasks are validated and can only represent any existing combination
of bits corresponding to existing cases.

### tryMask

This method works the same as `fromMask`, but will return a `Bitmask`
with a value of 0 or a specified bitmask instead if an invalid
bitmask is given.

````php
Permission::tryMask(null); // returns a Bitmask object with value 0
Permission::tryMask(
    null,
    Permission::Read
); // returns a Bitmask object with value 1
Permission::tryMask(0); // returns a Bitmask object with value 0
Permission::tryMask(
    0,
    Permission::Read
); // returns a Bitmask object with value 0

Permission::tryMask(1); // returns Bitmask with value 1
Permission::tryMask(5); // returns Bitmask with value 5
Permission::tryMask(16); // returns a Bitmask object with value 0
Permission::tryMask(16, 'read'); // returns a Bitmask object with value 1

Permission::tryMask(
    16,
    Permission::Read
); // returns a Bitmask object with value 1
Permission::tryMask(
    16,
    Permission::mask('Read', 'Update')
); // returns a Bitmask object with value 3

PermissionInt::tryMask(32); // returns a Bitmask object with value 32
PermissionInt::tryMask(64); // returns a Bitmask object with value 0
PermissionInt::tryMask(
    64,
    'Read', 'Update'
); // returns a Bitmask object with value 3
````

Note: If [Defaults](defaults.md) is configured, tryMask will use that default.
You can counteract by passing a null as the second value.

## Bitmask operations

The `Bitmask` object operates a similar to a `Flag Bag` you see in other libraries.
You can easily modify and validate. It is however tied to the enum that created it.

The `Bitmask` object is also implementing a fluent interface for all methods, unless
specified otherwise.

All the methods accept either enums or strings or integers representing
the enums either mapped or directly. You can also insert Bitmask instances
as (one of) it's argument, as long as the Bitmask belongs to the same enum.

### Value

````php
Permission::mask()->value(); // returns 0
Permission::mask('Read')->value(); // returns 2
Permission::mask('Create', 'Read')->value(); // returns 3
````

#### Stringable

Bitmask objects are stringable. It will return the value as a string

````php
(string)Permission::mask(); // returns "0"
(string)Permission::mask('Read'); // returns "2"
(string)Permission::mask('Create', 'Read'); // returns "3"
````

### Cases

Returns an array of cases corresponding to the bits set.

````php
Permission::mask()->cases(); // returns []
Permission::mask('Read')->cases(); // returns [Permission::Read]
Permission::mask('Create', 'Read')->cases(); // returns [Permission::Create, Permission::Read]
````

### Set

````php
Permission::mask()->set(Permission::Read); // sets value to 2
Permission::mask('Read')->set(Permission::Create); // sets value to 3
Permission::mask('Read')
    ->set(Permission::mask(Permission::Create)); // sets value to 3
Permission::mask('Read')->set(Permission::Create, 'Update'); // sets value to 7
Permission::mask('Read')->set(Permission::Read); // keeps value as 2
````

### Unset

````php
Permission::mask()->unset(Permission::Read); // keeps value as 0
Permission::mask('Read')->unset(Permission::Read); // sets value to 0
Permission::mask('Read')
             ->unset(Permission::mask(Permission::Read)); // sets value to 0
Permission::mask('Read', 'Update')
             ->unset(Permission::Create, 'Update'); // sets value to 2
Permission::mask('Read')->unset(Permission::Read); // keeps value as 2
````

### Toggle

Toggle toggles between 0 and 1. If a bit is set, it wil unset it and vice versa.
You can mix up as many as you like, it will toggle each value individually.

````php
Permission::mask()->toggle(Permission::Read); // sets value to 1
Permission::mask('Read')->toggle(Permission::Read); // sets value to 0
Permission::mask()
            ->toggle(Permission::mask(Permission::Read)); // sets value to 1
Permission::mask('Read', 'Update')
            ->toggle(Permission::Create, 'Update'); // sets value to 3
Permission::mask('Update')->toggle(Permission::Read); // sets value to 6
````

### Clear

Resets the value to 0. This means no bits are set after calling it.

````php
Permission::mask()->clear(); // sets value to 0
Permission::mask('Read')->clear(); // sets value to 0
Permission::mask('Read', 'Update')->clear(); // sets value to 0
````

### Copy

Copy allows you to duplicate the `Bitmask`. It creates a whole new instance,
with the current value, you can modify, without modifying the original one.

````php
Permission::mask()->copy(); // returns new Bitmask instance.
````

### Has

````php
Permission::mask()->has('Read'); // returns false
Permission::mask('Read')->has(Permission::Read); // returns true
Permission::mask('Create')->has('Read'); // returns false
````

### All

````php
Permission::mask()->all(); // returns true
Permission::mask()->all('Read', 'Create'); // returns false
Permission::mask('Read')->all('Read', 'Create'); // returns false
Permission::mask('Read', 'Update', 'Delete')->all('Read', 'Update'); // returns true
````

### Any

````php
Permission::mask()->all(); // returns true
Permission::mask()->all('Read', 'Create'); // returns false
Permission::mask('Read')->all('Read', 'Create'); // returns false
Permission::mask('Read', 'Update', 'Delete')->all('Read', 'Update'); // returns true
````

### Xor

````php
Permission::mask()->xor(); // returns false
Permission::mask()->xor('Read', 'Create'); // returns false
Permission::mask('Read')->xor('Read', 'Create'); // returns true
Permission::mask('Read', 'Update')->xor('Read', 'Update'); // returns false
````

### None

````php
Permission::mask()->none(); // returns true
Permission::mask()->none('Read', 'Create'); // returns true
Permission::mask('Read')->none('Read', 'Create'); // returns false
Permission::mask('Read', 'Update')->none('Delete', 'Create'); // returns true
````

### for

Bitmask does it for you, but if you want to check up front, you can check if
the Bitmask belongs to a certain enum.

````php
Permission::mask()->for(Permission::class); // returns true
Permission::mask()->for(PermissionInt::class); // returns false
````

### forOrFail

Does the same as `for`, but instead throws an exception when it does not match.

````php
Permission::mask()->for(Permission::class); // returns true
Permission::mask()->for(PermissionInt::class); // throws exception
````

### ForEnum

Returns the class name of the enum the Bitmask belongs to.

````php
Permission::mask()->forEnum(); // returns Permission::class
PermissionInt::mask()->forEnum(); // returns PermissionInt::class
````

### Laravel Casting
For details on integrating bitmask enums with Eloquent models, see [Laravel Casting](casting.md#bitmask).
