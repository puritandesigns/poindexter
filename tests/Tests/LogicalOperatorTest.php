<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Poindexter\Parsing\Parser;

class LogicalOperatorTest extends TestCase
{
    public function test_and()
    {
        $this->assertEquals(1, Parser::calculate('1 & 1')->getValue());

        $this->assertEquals(1, Parser::calculate('1 & 1 & 1')->getValue());

        $this->assertEquals(0, Parser::calculate('1 & 0')->getValue());

        $this->assertEquals(0, Parser::calculate('1 & 1 & 0')->getValue());

        $this->assertEquals(1, Parser::calculate(
            'number > -2 & truthy_value = 1 & (5 * 25)',
            ['number' => -1, 'truthy_value' => true]
        )->getValue());
    }

    public function test_or()
    {
        $this->assertEquals(1, Parser::calculate('1 | 0')->getValue());

        $this->assertEquals(1, Parser::calculate('1 | 1')->getValue());

        $this->assertEquals(0, Parser::calculate('0 | 0')->getValue());
    }
}
