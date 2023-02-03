# Defaults

Defaults give you some control over the configuration of
the default value.

## Usage

The basic way to specify a `Default` is by adding a case.

```php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Defaults;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;
    case Default;
}

Suit::default(); // returns Suit::Default
Suit::Default->isDefault(); // returns true
Suit::Default->isNotDefault(); // returns false
Suit::Spades->isDefault(); // returns true
Suit::Spades->isNotDefault(); // returns true
````

NOTE: the default method returns a `null` when no default is set.

When using [From](from.md) or [Getters](getters.md)

````php
Suit::from('default'); // returns Suit::Default
Suit::from('circles'); // throws ValueError
Suit::tryFrom('circles'); // returns Suit::Default

Suit::get('default'); // returns Suit::Default
Suit::get('circles'); // throws ValueError
Suit::tryGet('circles', true); // returns Suit::Default
Suit::tryGet('circles'); // returns null
````

## The default keyword

Everywhere you use the string `default`, Enumhancer uses what
ever default you have configured.

## Configuring using constant

Defaults are primarily configured using a constant named
`Default`.

```php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Defaults;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;

    const Default = Suit::Hearts;
}

Suit::default(); // returns Suit::Hearts
Suit::Default->isDefault(); // returns true
Suit::Default->isNotDefault(); // returns false
Suit::Hearts->isDefault(); // returns true
Suit::Hearts->isNotDefault(); // returns false
Suit::Spades->isDefault(); // returns true
Suit::Spades->isNotDefault(); // returns true
````

When using [From](from.md) or [Getters](getters.md)

````php
Suit::from('default'); // returns Suit::Hearts
Suit::from('circles'); // throws ValueError
Suit::tryFrom('circles'); // returns Suit::Hearts

Suit::get('default'); // returns Suit::Hearts
Suit::get('circles'); // throws ValueError
Suit::tryGet('circles'); // returns Suit::Hearts
````

NOTE: Be aware that a constant `Default` always has to be an
instance of the enum they are in.

### Private constant

When you don't want developers to use the constant
directly, you can make the constant private.

```php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Defaults;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;

    private const Default = Suit::Hearts;
}
```

## Configuring using method

Another way to set a default is by overriding the default
method.

```php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Defaults;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;

    public static function default(): ?self
    {
        return Suit::Spades;
    }
}
```

When the method returns `null`, no default is set.

## Configuring defaults

see [ConfigureDefaults](configure.md#configuredefaults)

## Precedence

The order of precedence is as follows:

- Overridden `default` method
- `ConfigureDefaults`
- mapped values using [Mappers](mappers.md)
- one of `const` or `case` in order of appearance (uppercase or lowercase)
