# Implicit enum binding

Laravel already supports enum binding for routes out of the box. Enumhancer
just adds some of it's famous secret flavor to level up it's potential.

This feature is loaded out of the box and does not alone allow you to bind
basic enums, but also allows you to use features like [Mappers](mappers.md) on
both basic as string and int backed enums as well.

## binding a simple enum

````php
enum Suit {
    case Hearts;
    case Diamonds;
    case Spades;
    case Clubs;
}

Illuminate\Support\Facades\Route::get(
    '/card/{card}',
    function (Suit $card) {
        print $card->name;
    }
);

/card/Hearts // prints 'Hearts'
/card/diamonds // prints 'Diamonds'
/card/0 // prints 'Hearts'

````

## binding optionally

````php
enum Suit {
    case Hearts;
    case Diamonds;
    case Spades;
    case Clubs;
}

Illuminate\Support\Facades\Route::get(
    '/card/{card?}',
    function (Suit $card = null) {
        print $card->name ?? 'nothing';
    }
);

/card/Hearts // prints 'Hearts'
/card/ // prints 'nothing'

````

## binding optionally with default

````php
enum Suit {
    case Hearts;
    case Diamonds;
    case Spades;
    case Clubs;

    const Default = Suit::Clubs;
}

Illuminate\Support\Facades\Route::get(
    '/card/{card?}',
    function (Suit $card) {
        print $card->name;
    }
);

/card/Hearts // prints 'Hearts'
/card/ // prints 'Clubs'
````

## Binding an int-backed enum

Binding an int-backed enum to your routes is just as easy as with basic enums.

````php
enum Suit: int {
    case Hearts = 1;
    case Diamonds = 5 ;
    case Spades = 10 ;
    case Clubs = 15;
}

Illuminate\Support\Facades\Route::get(
    '/card/{card}',
    function (Suit $card) {
        print $card->name;
    }
);

/card/Hearts // prints 'Hearts'
/card/diamonds // prints 'Diamonds'
/card/0 // prints 'Hearts'
/card/15 // prints 'Clubs'

````

## Binding a string-backed enum

Laravel has its own enum binding in place for string-backed enums. Under the
hood, Enumhancer is just adding another middleware and gives it priority over
Laravel's binding middleware. When a string-backed enum is used, Enumhancer maps
the given value if needed and replaces the requested parameter value with the
correct value.
