# Dropdown

Dropdown can be used for static dropdowns on your website. It works in
combination with [Labels](labels.md)

## usage

````php
use Henzeb\Enumhancer\Concerns\Dropdown;

enum Color {

    use Dropdown;

    case Red;
    case Green;
    case Blue;
}

enum Animal: int {

    use Dropdown;

    case Cat = 2;
    case Dog = 3;
    case Mouse 5;
}

enum AnimalLabeled: int {

    use Dropdown, Labels;

    case Cat = 2;
    case Dog = 3;
    case Mouse 5;

    public static function labels(): array
    {
        return [
            'cat' => 'feline',
            'dog' => 'canine',
            'mouse' => 'mus musculus'
        ]
    }
}

enum Fruit: string {

    use Dropdown;

    case Apple = 'apple';
    case Orange = 'orange';
    case Banana = 'banana';
}

enum FruitLabeled {

    use Dropdown, Labels;

    case Apple;
    case Orange;
    case Banana;

    public static function labels(): array
    {
        return [
            'apple' => 'an apple',
            'orange' => 'an orange',
            'banana' => 'a banana'
        ]
    }
}

````

### examples

````php
/** With unit enum */
Color::dropdown(); // ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue']

/** Keep enum value case */
Color::dropdown(true); // ['Red' => 'Red', 'Green' => 'Green', 'Blue' => 'Blue']

/** int backed enums */
Animal::dropdown(); // [2 => 'Cat', 3 => 'Dog', 5 => 'Mouse']

/** int backed enums with labels*/
AnimalLabeled::dropdown(); // [2 => 'feline', 3 => 'canine', 5 => 'mus musculus']

/** string backed enums */
Fruit::dropdown(); // ['apple' => 'Apple', 'orange' => 'Orange', 'banana' => 'Banana']
````
