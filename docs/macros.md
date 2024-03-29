# Macros

If you use my library inside your own package, you may want to allow people to
'extend' the functionality on your enum object. Extension is not possible, and
you can't use Laravel's or Spatie's `Macroable` as this is using a property,
and properties are not allowed on an enum as state is forbidden.

NOTE: Macros are not enabled by default.

Also be aware that macros will follow PHP standards. Meaning that static
macro's can be called statically and non-statically, but non-static
macros can only be called non-statically.

## Example

````php
enum Suit {
    use Henzeb\Enumhancer\Concerns\Macros;

    case Hearts;
    case Clubs;
    // ...
}
````

## Macro

````php
/** static **/
Suit::macro('shuffle', static fn() => self::cases()[array_rand(self::cases())] );
Suit::shuffle(); // returns one of the available cases
Suit::Hearts->shuffle(); // returns one of the available cases

/** non-static **/
Suit::macro('serialize', fn(bool $json) => $json?json_encode($this->name):serialize($this));
Suit::Hearts->serialize(true); // returns "Hearts"
Suit::Hearts->serialize(false); // returns E:11:"Suit:Hearts"
Suit::serialize(true); // throws fatal error
````

## Mixin

Just like other libraries, `Macros` supports Mixin.

````php
class SuitMixin {
    public function shuffle(): Closure
    {
        return static fn() => self::cases()[array_rand(self::cases())];
    }

    public function serialize(): Closure
    {
        return fn(bool $json) => $json?json_encode($this->name):serialize($this);
    }
}

Suit::mixin(SuitMixin::class);
Suit::mixin(new SuitMixin());
````

## hasMacro

This method will allow you to test if a method exists before using it.

````php
Suit::hasMacro('toJson'); // returns true if exists, false otherwise
````

## flushMacros

You may want to flush macro's in certain situations. This will only flush
macro's for the enum it's called on.

````php
Suit::flushMacros(); // only flushes macro's belonging to Suit
````

## Global macro's

Enumhancer also supports global macro's, which will be available to you on
every enum using the `Macros` trait.

````php
\Henzeb\Enumhancer\Helpers\Enumhancer::macro('macro', fn()=>true);

\Henzeb\Enumhancer\Helpers\Enumhancer::mixin(GlobalMixin::class);
\Henzeb\Enumhancer\Helpers\Enumhancer::mixin(new GlobalMxin());

\Henzeb\Enumhancer\Helpers\Enumhancer::flushMacros();
````

Note: flushing macro's using the global method only flushes global macro's.
flushing macro's on an enum will not touch the global macro's
