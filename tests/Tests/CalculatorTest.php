<?php

namespace Tests;

use Implementation\Factor;
use Implementation\Functor;
use Implementation\Integer;
use Implementation\Number;
use PHPUnit\Framework\TestCase;
use Poindexter\Calculator;
use Poindexter\Factors\Multiply;
use Poindexter\Interfaces\ResultInterface;

class CalculatorTest extends TestCase
{
    public function test_simple_add_calculation()
    {
        $factors = [];

        $factors[] = new Number(1, 'integer');

        $factors[] = new Functor('add');

        $factors[] = new Number(1, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(2, $actual);
    }

    public function test_simple_multiply_calculation()
    {
        $factors = [];

        $factors[] = new Number(2, 'integer');

        $factors[] = new Multiply();

        $factors[] = new Number(2, 'integer');

        $calculator = new Calculator($factors, ResultInterface::INTEGER);

        $actual = $calculator->calculate();

        $this->assertEquals(4, $actual);
    }

    public function test_simple_subtract_calculation()
    {
        /** @var \Conflux\Calculator $calculator */
        $calculator = factory(\Conflux\Calculator::class)->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 2]);

        factory(\Conflux\CalculatorFactor::class)
            ->state('subtract')
            ->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 1]);

        $actual = $calculator->calculate();

        $this->assertEquals(1, $actual);
    }

    public function test_simple_divide_calculation()
    {
        /** @var \Conflux\Calculator $calculator */
        $calculator = factory(\Conflux\Calculator::class)->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 4]);

        factory(\Conflux\CalculatorFactor::class)
            ->state('divide')
            ->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 2]);

        $actual = $calculator->calculate();

        $this->assertEquals(2, $actual);
    }

    public function test_multiple_add_calculations()
    {
        /** @var \Conflux\Calculator $calculator */
        $calculator = factory(\Conflux\Calculator::class)->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 1]);

        factory(\Conflux\CalculatorFactor::class)->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 1]);

        factory(\Conflux\CalculatorFactor::class)->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 1]);

        $actual = $calculator->calculate();

        $this->assertEquals(3, $actual);
    }

    public function test_concrete_formula_calculation()
    {
        /** @var \Conflux\Calculator $calculator */
        $calculator = factory(\Conflux\Calculator::class)->create([
            'result_type' => 'float'
        ]);

        factory(\Conflux\CalculatorFactor::class)
            ->state('variable')
            ->create(['value' => 'thickness']);

        factory(\Conflux\CalculatorFactor::class)
            ->state('divide')
            ->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => 12]);

        factory(\Conflux\CalculatorFactor::class)
            ->state('multiply')
            ->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('variable')
            ->create(['value' => 'width']);

        factory(\Conflux\CalculatorFactor::class)
            ->state('multiply')
            ->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('variable')
            ->create(['value' => 'height']);

        factory(\Conflux\CalculatorFactor::class)
            ->state('multiply')
            ->create();

        factory(\Conflux\CalculatorFactor::class)
            ->state('number')
            ->create(['value' => .037, 'variable_type' => 'float']);

        $actual = $calculator->calculate([
            'width' => 10, 'height' => 10, 'thickness' => 4,
        ]);

        $this->assertEquals(1.23, round($actual, 2));
    }
}
