<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Poindexter\Exceptions\ParseException;
use Poindexter\Parsing\Parser;

class ParserTest extends TestCase
{
    public function test_parse_simple_statement()
    {
        $statement = '3 + 4';

        $result = Parser::calculate($statement);

        $this->assertEquals(7, $result->getValue());
    }

    public function test_parse_statement_with_gator()
    {
        $statement = '3 < 4';

        $result = Parser::calculate($statement);

        $this->assertEquals(1, $result->getValue());
    }

    public function test_parse_statement_with_equals()
    {
        $this->assertEquals(0, Parser::calculate('3 = 4')->getValue());

        $this->assertEquals(1, Parser::calculate('4 = 4')->getValue());

        $this->assertEquals(
            1,
            Parser::calculate(
                'location_id = 99',
                ['location_id' => 99]
            )->getValue()
        );

        $this->assertEquals(
            0,
            Parser::calculate(
                'location_id = 99',
                ['location_id' => 95]
            )->getValue()
        );
    }

    public function test_parse_statement_with_gator_and_variable()
    {
        $statement = '3 <= :variable';

        $result = Parser::calculate($statement, [':variable' => 5]);
        $this->assertEquals(1, $result->getValue());


        $result = Parser::calculate($statement, [':variable' => 3]);
        $this->assertEquals(1, $result->getValue());


        $result = Parser::calculate($statement, [':variable' => 1]);
        $this->assertEquals(0, $result->getValue());
    }

    public function test_parse_statement_with_gator_and_multiple_variables()
    {
        $statement = ':var_one <= var2';

        $result = Parser::calculate($statement, [':var_one' => 5, 'var2' => 7]);
        $this->assertEquals(1, $result->getValue());


        $result = Parser::calculate($statement, [':var_one' => 5, 'var2' => 5]);
        $this->assertEquals(1, $result->getValue());


        $result = Parser::calculate($statement, [':var_one' => 20, 'var2' => 7]);
        $this->assertEquals(0, $result->getValue());
    }

    public function test_parse_multiple_parens()
    {
        $statement = '(1 + 2) + (:test_first * 2) - (-2 + 2)';
        
        $result = Parser::calculate($statement, [':test_first' => 25]);

        $this->assertEquals(53, $result->getValue());
    }

    public function test_parse_errors_on_nested_parens()
    {
        try {
            Parser::calculate('(1 + (2 + 3))');

            $this->fail(
                'Should have thrown a ParseException for nested parentheses'
            );
        } catch (ParseException $e) {
            $this->assertEquals(
                'Currently cannot parse nested parentheses',
                $e->getMessage()
            );
        }
    }
}
