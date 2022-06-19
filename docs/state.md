# State

State is a feature that allows you to use enums state-driven. Think of state
pattern. Out of the box it operates like a one-way traffic-light, from top to
bottom. But you can make it as complex as you need it to be.

## Usage

```php
use Henzeb\Enumhancer\Concerns\State;

enum elevator
{
    use State;

    case Open;
    case Close;
    case Move;
    case Stop;
}
```

### examples

#### Basic usage

```php
elevator::Open->allowsTransition('Close'); // returns true
elevator::Open->allowsTransition(elevator::Move); // returns false

elevator::Open->transitionTo('Close'); // returns elevator::Close
elevator::Move->transitionTo(elevator::Close); // throws exception
elevator::Close->transitionTo('Open'); // throws exception
```

#### Complex Usage

```php
use Henzeb\Enumhancer\Concerns\State;

enum elevator
{
    use State;

    case Open;
    case Close;
    case Move;
    case Stop;

    public static function customTransitions(): array
    {
        return [
            'open' => 'close',
            'close' => [
                'open',
                'move'
            ],
            'move' => 'stop',
            'stop' => 'open'
        ];
    }
}

elevator::Open->transitionTo(elevator::Close)
    ->transitionTo(elevator::Open)
    ->transitionTo(elevator::Close)
    ->transitionTo('Move')
    ->transitionTo(elevator::Stop)
    ->transitionTo('Open')
    ->transitionTo('Close'); //eventually returns elevator::Close

elevator::Move->transitionTo('Open'); //throws exception
```

The array returned by the `customTransitions` method can return an array containing
the name or value as key, and the name, value or enum instance as value.

Note: You can only go one level deep.

#### Allowed transitions

When you want to present the transition options to your user, you can use
`allowedTransitions` to get them (complex example):

```php
elevator::Open->allowedTransitions(); // returns [elevator::Close]
elevator::Close->allowedTransitions(); // returns [elevator::Open, elevator::Move]
elevator::Move->allowedTransitions(); // returns [elevator::Stop]
elevator::Stop->allowedTransitions(); // returns [elevator::Open]
```

Note: when there are no transitions possible, `allowedTranslations` returns an
empty array

#### Transition hooks

Transition hooks will give you more granular control about transitions. It
allows you to allow a transaction based on a condition and it also allows you to
do some other things when a transaction occurs.

For example: You could use it to allow stopping the elevator only when the floor
is reached and you can change the current level by every move up or down.

We start out with making an `TransitionHook` class. The method-names are based
on the enum's name. cases don't really matter here, just like always.

- allow\<From name\>\<To name\>
- \<From name\>\<To name\>

Example:

```php
use Henzeb\Enumhancer\Contracts\TransitionHook;

class ElevatorHooks extends TransitionHook {
    private int $floor = 1;
    protected function allowMoveStop(): bool
    {
        return false;
    }

    protected function closeMove() {
        $this->floor++;
    }
}
```

```php
$hooks = new ElevatorHooks();
elevator::Move->allowTransition('stop', $hook); // returns false
elevator::Move->allowedTransitions($hook); // returns []
elevator::Move->transitionTo('stop', $hook); // throws IllegalEnumTransitionException
elevator::Close->allowTransition('move', $hook); // returns true
elevator::Close->transitionTo('move', $hook); // returns increases $floor to 2
```

You can also add a `TransitionHook` directly on to your enum class.

```php
enum elevator {
    use State;

    public static function transitionHooks(): ?TransitionHooks
    {
        return new ElevatorHooks();
    }
}
```

If you use both ways, both need to return true, in order to allow a transition.

### Validation (Laravel)

see [State](laravel.validation.md#EnumTransition)

### Casting (Laravel)

see [Casting](casting.md#state)
