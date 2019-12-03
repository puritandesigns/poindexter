<?php

namespace Tests;

use Implementation\Factor;
use Implementation\Functor;
use Implementation\Integer;
use PHPUnit\Framework\TestCase;
use Poindexter\Calculator;

class CalculatorParenthesisTest extends TestCase
{
    public function test_nesting_parens()
    {
        $factors = [];

        $factors[] = new Factor(['type' => 'parenthesis_open']);

            $factors[] = new Factor(['type' => 'parenthesis_open']);

                $factors[] = new Factor(['type' => 'parenthesis_open']);

                    $factors[] = new Integer(1);

                $factors[] = new Factor(['type' => 'parenthesis_close']);

            $factors[] = new Factor(['type' => 'parenthesis_close']);

        $factors[] = new Factor(['type' => 'parenthesis_close']);

        $calculator = new Calculator($factors);
//For some reason, parseStatements returns an array with 3 indices:
//    0 => Parenthesis (with the number)
//    1 => Parenthesis (that is empty)
//    2 => Number (the same one that is in the first parenthesis)
//What should be returned is just the one parenthesis with the number
        $actual = $calculator->calculate();

        $this->assertEquals(1, $actual->getValue());
    }

    public function test_parenthesis_formula()
    {
        $factors = [];

        $factors[] = new Factor(['type' => 'parenthesis_open']);

            $factors[] = new Integer(1);

            $factors[] = new Functor('add');


            $factors[] = new Factor(['type' => 'parenthesis_open']);

                $factors[] = new Integer(1);

                $factors[] = new Functor('multiply');

                $factors[] = new Integer(2);

            $factors[] = new Factor(['type' => 'parenthesis_close']);

            $factors[] = new Functor('add');

            $factors[] = new Integer(1);

        $factors[] = new Factor(['type' => 'parenthesis_close']);

        $factors[] = new Functor('add');

        $factors[] = new Integer(1);

        $calculator = new Calculator($factors, 'integer');

        $actual = $calculator->calculate();

        $this->assertEquals(5, $actual);
    }
}
