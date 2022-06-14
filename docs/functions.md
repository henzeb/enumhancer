# Helper functions

To accommodate situations that haven't been solved (yet), a few helper
functions.

Each helper function that works with values has a lowercase version for easy
usage.

Each helper function has a full name version and a shortcut, just pick your
personal favorite poison.

Each function also accepts null and returns null when this happens. This eases
usage with optional situations.

## Backing

In some situations, libraries or frameworks only accept backed enumerations
and/or Stringable objects. Or you want to use the name/value of either backed or
basic enums. This method gives you a proxy object with name and value
properties.

Note: an example of where this is useful is in Laravel's Query Builder.

### Examples

```php
enum MyEnum {
    case Enum;
    case Other;
}

enum MyStringEnum: string {
    case String = 'My string';
}

use Henzeb\Enumhancer\Functions\b;
use Henzeb\Enumhancer\Functions\backing;
use Henzeb\Enumhancer\Functions\bl;
use Henzeb\Enumhancer\Functions\backingLowercase;

(MyEnum::Enum)->name; // Enum
b(MyEnum::Enum)->value; // Enum
(string)b(MyEnum::Enum); // Enum

b(MyEnum::Enum, false)->value; // enum
(string)b(MyEnum::Enum, false); // enum

b(MyStringEnum::String, false)->value; // My string
(string)b(MyStringEnum::String, false); // My string

backing(MyEnum::Enum)->name; // Enum
backing(MyEnum::Enum)->value; // Enum
(string)backing(MyEnum::Enum); // Enum

backing(MyEnum::Enum, false)->value; // enum
(string)backing(MyEnum::Enum, false); // enum

backing(MyStringEnum::String, false)->value; // My string
(string)backing(MyStringEnum::String, false); // My string

# Lower case
bl(MyEnum::Enum)->name; // Enum
bl(MyEnum::Enum)->value; // enum
(string)bl(MyEnum::Enum); // enum

bl(MyStringEnum::String)->value; // My string
(string)bl(MyStringEnum::String); // My string

backingLowercase(MyEnum::Enum)->name; // Enum
backingLowercase(MyEnum::Enum)->value; // enum
(string)backingLowercase(MyEnum::Enum); // enum

backingLowercase(MyStringEnum::String)->value; // My string
(string)backingLowercase(MyStringEnum::String); // My string

```

## Name

This function is particular useful when you want to use it as an array key.

```php
enum MyEnum {
    case Enum;
}

use Henzeb\Enumhancer\Functions\n;
use Henzeb\Enumhancer\Functions\name;

n(MyEnum::Enum); // Enum
name(MyEnum::Enum); // Enum
```

## Value

This function returns the value of your Enum. Works just like
[Value](value.md), except for the lower case variants which return the lower
case version of the enum name.

```php
enum MyEnum {
    case Enum;
}

enum MyStringEnum: string {
    case String = 'String';
}

use Henzeb\Enumhancer\Functions\v;
use Henzeb\Enumhancer\Functions\value;
use Henzeb\Enumhancer\Functions\vl;
use Henzeb\Enumhancer\Functions\valueLowercase;

v(MyEnum::Enum); // Enum
v(MyEnum::Enum, false); // enum
v(MyStringEnum::String, false); // My string

value(MyEnum::Enum); // Enum
value(MyEnum::Enum, false); // enum
value(MyStringEnum::String, false); // My string

# Lower case
vl(MyEnum::Enum); // enum
vl(MyStringEnum::String); // My string

valueLowercase(MyEnum::Enum); // enum
valueLowercase(MyStringEnum::String); // My string

```
