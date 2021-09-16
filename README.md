# Poindexter
A formula-calculating library for php.

## Installation
You can install this package with composer:
```bash
composer require puritandesigns/poindexter
```

## Usage

Here are a few usage examples:

```php
use Poindexter\Parsing\Parser;

// Simple arithmetic
Parser::calculate('1 + 1')->getValue(); // returns 2
Parser::calculate('2 * 2')->getValue(); // returns 4

// Comparisons
// returns 1 (truthy)
Parser::calculate('1 < 2')->getValue();
Parser::calculate('2 = 2')->getValue();
// returns 0 (falsy)
Parser::calculate('2 <= 1')->getValue();
Parser::calculate('1 = 2')->getValue();

// Variables
Parser::calculate('x + y', ['x' => 1, 'y' => 2])->getValue();
Parser::calculate('long_name + y', ['long_name' => 1, 'y' => 2])->getValue();

// Simple parentheses
Parser::calculate('(1 + x) * (1 + y)', ['x' => 1, 'y' => 2])->getValue();
```

## Caveats
A couple of things to keep in mind....

### The Parser is pretty lazy.
- It cannot parse nested parentheses
- It needs a space between elements:
    - `x+y` would be understood as a variable instead of 3 distinct statements
    - `x + y` would be understood as variable x adding with variable y

### You can manually build more-complicated statements from Factors objects
```php
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Factors;

$factors = [
    new Factors\Number(5),
    new Factors\Add(),
    new Factors\Parenthesis([
        new Factors\Number(5),
        new Factors\Add(),
        new Factors\Parenthesis([
            new Factors\Number(5),
            new Factors\Add(),
            new Factors\Number(5)
        ])
    ]),
    new Factors\Divide(),
    new Factors\Number(5)
];

$calculator = new \Poindexter\Calculator($factors, ResultInterface::INTEGER);

$calculator->calculate()->getValue();
```

See tests for more examples.

### Be careful with Comparators
Incorporating `<`, `>`, `=`, etc... yields a binary (1 or 0) result for that particular expression. If you are not careful, that might change the intention of your formulas.
