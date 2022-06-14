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

    public static function transitions(): array
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

The array returned by the `transitions` method can return an array containing
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

### Validation (laravel)

see [State](laravel.validation.md#state)
