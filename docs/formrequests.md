# Form Requests

Out of the box, Laravel has a decent method called `enum` on their FormRequest
object. In many cases this may be enough.

## asEnum

This method works exactly like Laravel's `enum` method, but when
you are using a backed enum and want to rely on features as
[Defaults](defaults.md) or when want to pass conditional
[Mappers](mappers.md), you want to use this method.

```php
enum Suit: int {
    use \Henzeb\Enumhancer\Concerns\Defaults;

    private const Default = self::Hearts;

    case Hearts = 1;
    case Diamonds = 2;
}

# Request: /card/?card=diamonds
$request->enum('suit', Suit::class); // returns Suit::Diamonds
$request->asEnum('suit', Suit::class); // returns Suit::Diamonds

# Request: /card/?card=
$request->enum('suit', Suit::class); // returns null
$request->asEnum('suit', Suit::class); // returns Suit::Hearts

```

## Mappers

You can pass as many mappers as you need.

```php
enum Suit: int {
use \Henzeb\Enumhancer\Concerns\Defaults;

    case Hearts;
    case Diamonds;
}

$request->asEnum('suit', Suit::class, ['heart'=>'hearts']);
$request->asEnum('suit', Suit::class, new SuitMapper());
$request->asEnum('suit', Suit::class, new SuitMapper(), ['heart'=>'hearts']);
$request->asEnum('suit', Suit::class, SuitMapper::class);
```
