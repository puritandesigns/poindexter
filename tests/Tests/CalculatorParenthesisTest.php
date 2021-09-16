<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Poindexter\Calculator;
use Poindexter\Factors\Add;
use Poindexter\Factors\Multiply;
use Poindexter\Factors\Number;
use Poindexter\Factors\Parenthesis;
use Poindexter\Interfaces\ResultInterface;

class CalculatorParenthesisTest extends TestCase
{
    public function test_nesting_parens()
    {
        $factors = [];

        $factors[] = new Parenthesis([
            new Parenthesis([
                new Number(1, ResultInterface::INTEGER)
            ])
        ]);

        $calculator = new Calculator($factors);

        $actual = $calculator->calculate();

        $this->assertEquals(1, $actual->getValue());
    }

    public function test_parenthesis_formula()
    {
        $inner_parenthesis = new Parenthesis([
            new Number(1, ResultInterface::INTEGER),
            new Multiply(),
            new Number(2, ResultInterface::INTEGER),
        ]);

        $factors = [
            new Parenthesis([
                new Number(1, ResultInterface::INTEGER),
                new Add(),
                $inner_parenthesis,
                new Add(),
                new Number(1, ResultInterface::INTEGER)
            ]),
            new Add(),
            new Number(1, ResultInterface::INTEGER)
        ];

        $this->assertEquals(5, Calculator::calculateInt($factors));
    }
}
