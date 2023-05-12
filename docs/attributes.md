# Attributes

Enumhancer supports [Attributes](https://www.php.net/manual/en/language.attributes.overview.php).
You simply declare your attribute and Enumhancer will allow you to access them.

In most situations, you want to define a method to get the value nicely,
so the `getAttribute` method is `protected` by default.

## Fetch a single case attribute

````php
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Description {
    public function __construct(public string $value) {}
}
````

````php
enum Suit {
    use Henzeb\Enumhancer\Concerns\Attributes;
    
    #[Description('Suit of Hearts')]
    case Hearts;
    
    #[Description('Suit of Clubs')]
    case Clubs;
    
    #[Description('Suit of Spades')]
    case Spades;
    
    case Diamonds; 
    
    public function getDescription(): ?string
    {
        return $this->getAttribute(Description::class)?->value;
    }
}
````

````php
Suit::Hearts->getDescription(); // returns 'Card of Hearts'
Suit::Clubs->getDescription(); // returns 'Card of Clubs'
Suit::Diamonds->getDescription(); // returns null
````

## Fetch multiple case attributes

Sometimes, you might want to work with repeatable attributes.

````php
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Description {
    public function __construct(public string $value) {}
}

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Color {
    public function __construct(public string $value) {}
}
````

````php
enum Suit {
    use Henzeb\Enumhancer\Concerns\Attributes;
    
    #[Description('Card of Hearts'), Color('red')]
    case Hearts;
    
    #[Description('Card of Clubs'), Color('black')]
    case Clubs;
    
    #[Description('Card of Spades')]
    case Spades;
    
    case Diamonds; 
    
    public function getAllDescriptions(): []
    {
        return $this->getAttributes(Description::class);
    }
    
    public function getAllAttributes(): []
    {
        return $this->getAttributes();
    }
}
````

````php
Suit::Hearts->getAllDescriptions(); // returns [new Description('Card of Hearts')]
Suit::Diamonds->getAllDescriptions(); // returns []

Suit::Hearts->getAllAttributes(); // returns [new Description('Card of Hearts'), new Color('red')] 
Suit::Spades->getAllAttributes(); // returns [new Description('Card of Hearts')] 
````

## Fetching class attributes

````php
#[Attribute(Attribute::TARGET_CLASS)]
class Description {
    public function __construct(public string $value) {}
}

#[Attribute(Attribute::TARGET_CLASS)]
class CardCount {
    public function __construct(public int $value) {}
}
````

````php
#[Description('deck of cards'), CardCount(52)]
enum Suit {
    use Henzeb\Enumhancer\Concerns\Attributes;
    
    public static function getSuitDescription(): string
    {
        return self::getEnumAttribute(Description::class)->value
    }
    
    public static function getCardCount(): int
    {
        return self::getEnumAttribute(CardCount::class)->value
    }
    
    public static function getSuitDescriptions(): array
    {
        return self::getAttributes(Description::class);
    } 
    
    public static function getSuitAttributes(): array
    {
        return self::getAttributes();
    }  
}
````

````php
Suit::getSuitDescription(); // returns deck of cards
Suit::getCardcount(); // returns 52

Suit::getSuitDescriptions(); //returns [new Description('deck of cards')]
Suit::getSuitAttributes(); //returns [new Description('deck of cards'), new CardCount(52)]
````

