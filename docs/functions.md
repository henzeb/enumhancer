# Helper functions

To accommodate situations that haven't been solved (yet), a few helper
functions.

Each helper function that works with values has a lowercase version
for easy usage. 

Each helper function has a full name version and a shortcut, just pick your 
personal favorite poison.

Each function also accepts null and returns null when
this happens. This eases usage with optional situations.

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

\Henzeb\Enumhancer\Functions\b(MyEnum::Enum)->name; // Enum
\Henzeb\Enumhancer\Functions\b(MyEnum::Enum)->value; // Enum
(string)\Henzeb\Enumhancer\Functions\b(MyEnum::Enum); // Enum

\Henzeb\Enumhancer\Functions\b(MyEnum::Enum, false)->value; // enum
(string)\Henzeb\Enumhancer\Functions\b(MyEnum::Enum, false); // enum

\Henzeb\Enumhancer\Functions\b(MyStringEnum::String, false)->value; // My string
(string)\Henzeb\Enumhancer\Functions\b(MyStringEnum::String, false); // My string

\Henzeb\Enumhancer\Functions\backing(MyEnum::Enum)->name; // Enum
\Henzeb\Enumhancer\Functions\backing(MyEnum::Enum)->value; // Enum
(string)\Henzeb\Enumhancer\Functions\backing(MyEnum::Enum); // Enum

\Henzeb\Enumhancer\Functions\backing(MyEnum::Enum, false)->value; // enum
(string)\Henzeb\Enumhancer\Functions\backing(MyEnum::Enum, false); // enum

\Henzeb\Enumhancer\Functions\backing(MyStringEnum::String, false)->value; // My string
(string)\Henzeb\Enumhancer\Functions\backing(MyStringEnum::String, false); // My string

# Lower case
\Henzeb\Enumhancer\Functions\bl(MyEnum::Enum)->name; // Enum
\Henzeb\Enumhancer\Functions\bl(MyEnum::Enum)->value; // enum
(string)\Henzeb\Enumhancer\Functions\bl(MyEnum::Enum); // enum

\Henzeb\Enumhancer\Functions\bl(MyStringEnum::String)->value; // My string
(string)\Henzeb\Enumhancer\Functions\bl(MyStringEnum::String); // My string

\Henzeb\Enumhancer\Functions\backingLowercase(MyEnum::Enum)->name; // Enum
\Henzeb\Enumhancer\Functions\backingLowercase(MyEnum::Enum)->value; // enum
(string)\Henzeb\Enumhancer\Functions\backingLowercase(MyEnum::Enum); // enum

\Henzeb\Enumhancer\Functions\backingLowercase(MyStringEnum::String)->value; // My string
(string)\Henzeb\Enumhancer\Functions\backingLowercase(MyStringEnum::String); // My string

```

## Name

This function is particular useful when you want to use it as an array key.

```php
enum MyEnum {
    case Enum;
}

\Henzeb\Enumhancer\Functions\n(MyEnum::Enum); // Enum
\Henzeb\Enumhancer\Functions\name(MyEnum::Enum); // Enum
```

## Value

This function returns the value of your Enum. Works just like
[Value](value.md), except for the lower case variants which return the
lower case version of the enum name.

```php
enum MyEnum {
    case Enum;
}

enum MyStringEnum: string {
    case String = 'String';
}

\Henzeb\Enumhancer\Functions\v(MyEnum::Enum); // Enum
\Henzeb\Enumhancer\Functions\v(MyEnum::Enum, false); // enum
\Henzeb\Enumhancer\Functions\v(MyStringEnum::String, false); // My string

\Henzeb\Enumhancer\Functions\value(MyEnum::Enum); // Enum
\Henzeb\Enumhancer\Functions\value(MyEnum::Enum, false); // enum
\Henzeb\Enumhancer\Functions\value(MyStringEnum::String, false); // My string

# Lower case
\Henzeb\Enumhancer\Functions\vl(MyEnum::Enum); // enum
\Henzeb\Enumhancer\Functions\vl(MyStringEnum::String); // My string

\Henzeb\Enumhancer\Functions\valueLowercase(MyEnum::Enum); // enum
\Henzeb\Enumhancer\Functions\valueLowercase(MyStringEnum::String); // My string

```
