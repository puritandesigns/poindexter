<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Poindexter\Calculator;
use Poindexter\Factors\Add;
use Poindexter\Factors\Equals;
use Poindexter\Factors\GreaterThan;
use Poindexter\Factors\LessThan;
use Poindexter\Factors\Number;
use Poindexter\Factors\Parenthesis;
use Poindexter\Factors\Variable;

class ComparatorTest extends TestCase
{
    public function test_greater_than()
    {
        $factors = [
            new Number(5),
            new GreaterThan(),
            new Number(3)
        ];

        $this->assertEquals(1, Calculator::calculateInt($factors));
    }

    public function test_greater_than_equals()
    {
        $factors = [
            new Number(5),
            new GreaterThan(true),
            new Number(5)
        ];

        $this->assertEquals(1, Calculator::calculateInt($factors));
    }

    public function test_less_than()
    {
        $factors = [
            new Number(5),
            new LessThan(),
            new Number(3)
        ];

        $this->assertEquals(0, Calculator::calculateInt($factors));
    }

    public function test_less_than_equals()
    {
        $factors = [
            new Number(5),
            new LessThan(true),
            new Number(5)
        ];

        $this->assertEquals(1, Calculator::calculateInt($factors));
    }

    public function test_greater_than_variables()
    {
        $factors = [
            new Variable('x'),
            new GreaterThan(),
            new Variable('y'),
        ];

        $this->assertEquals(
            1,
            Calculator::calculateInt($factors, ['x' => 10, 'y' => 1])
        );


        $factors = [
            new Variable('x'),
            new GreaterThan(),
            new Parenthesis([
                new Variable('y'),
                new Add(),
                new Number(10),
            ]),
        ];

        $this->assertEquals(
            0,
            Calculator::calculateInt($factors, ['x' => 10, 'y' => 5])
        );
    }

    public function test_equals()
    {
        $factors = [
            new Number(5),
            new Equals(),
            new Number(3)
        ];

        $this->assertEquals(0, Calculator::calculateInt($factors));

        $factors = [
            new Number(3),
            new Equals(),
            new Number(3)
        ];

        $this->assertEquals(1, Calculator::calculateInt($factors));
    }
}
