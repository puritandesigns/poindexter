<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Poindexter\Calculator;
use Poindexter\Factors\Add;
use Poindexter\Factors\Divide;
use Poindexter\Factors\Multiply;
use Poindexter\Factors\Number;
use Poindexter\Factors\Subtract;
use Poindexter\Factors\Variable;
use Poindexter\Interfaces\ResultInterface;

class CalculatorTest extends TestCase
{
    public function test_simple_add_calculation()
    {
        $factors = [];

        $factors[] = new Number(1, 'integer');

        $factors[] = new Add();

        $factors[] = new Number(1, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(2, $actual->getValue());
    }

    public function test_simple_multiply_calculation()
    {
        $factors = [];

        $factors[] = new Number(2, 'integer');

        $factors[] = new Multiply();

        $factors[] = new Number(2, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(4, $actual->getValue());
    }

    public function test_simple_subtract_calculation()
    {
        $factors = [];

        $factors[] = new Number(2, 'integer');

        $factors[] = new Subtract();

        $factors[] = new Number(1, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(1, $actual->getValue());
    }

    public function test_simple_divide_calculation()
    {
        $factors = [];

        $factors[] = new Number(4, 'integer');

        $factors[] = new Divide();

        $factors[] = new Number(2, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(2, $actual->getValue());
    }

    public function test_multiple_add_calculations()
    {
        $factors = [];

        $factors[] = new Number(1, 'integer');

        $factors[] = new Add();

        $factors[] = new Number(1, 'integer');

        $factors[] = new Add();

        $factors[] = new Number(1, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(3, $actual->getValue());
    }

    public function test_concrete_formula_calculation()
    {
        $factors = [];

        $factors[] = new Variable('thickness');

        $factors[] = new Divide();

        $factors[] = new Number(12, ResultInterface::INTEGER);

        $factors[] = new Multiply();

        $factors[] = new Variable('width');

        $factors[] = new Multiply();

        $factors[] = new Variable('height');

        $factors[] = new Multiply();

        $factors[] = new Number(.037);

        $calculator = new Calculator($factors);

        $actual = $calculator->calculate([
            'width' => 10, 'height' => 10, 'thickness' => 4,
        ]);

        $this->assertEquals(1.23, $actual->getValue(2));
    }
}
