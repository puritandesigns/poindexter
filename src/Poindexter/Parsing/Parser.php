<?php

namespace Poindexter\Parsing;

use Poindexter\Calculator;
use Poindexter\Exceptions\ParseException;
use Poindexter\Factors\Add;
use Poindexter\Factors\Divide;
use Poindexter\Factors\Equals;
use Poindexter\Factors\GreaterThan;
use Poindexter\Factors\GreaterThanEquals;
use Poindexter\Factors\LessThan;
use Poindexter\Factors\LessThanEquals;
use Poindexter\Factors\Multiply;
use Poindexter\Factors\Number;
use Poindexter\Factors\Subtract;
use Poindexter\Factors\Variable;

class Parser
{
    private const FUNCTORS = [
        '+' => Add::class,
        '-' => Subtract::class,
        '/' => Divide::class,
        '*' => Multiply::class,
        '>=' => GreaterThanEquals::class,
        '<=' => LessThanEquals::class,
        '=' => Equals::class,
        '>' => GreaterThan::class,
        '<' => LessThan::class,
    ];

    public static function calculate(
        string $statement,
        array $data = null,
        string $return_type = 'float'
    )
    {
        $factors = self::parse($statement);

        return Calculator::calculateResult($factors, $data, $return_type);
    }

    /**
     * @param string $statement
     * @return \Poindexter\Interfaces\FactorInterface[]
     */
    public static function parse(string $statement): array
    {
        $statement = trim($statement);

        $statements = [];

        while (strlen($statement)) {
            $paren_start_position = self::hasCharacter($statement, '(');

            if (false === $paren_start_position) {
                $statement = self::extractStatement(
                    $statements,
                    $statement,
                    strlen($statement)
                );
            } else {
                $statement = self::extractParenthesis(
                    $statement,
                    $statements,
                    $paren_start_position
                );
            }
        }

        $statements = self::parseStatementsIntoObjects($statements);

        return $statements;
    }

    private static function extractParenthesis(
        string $statement,
        array &$statements,
        int $paren_start_position
    ): string
    {
        if (0 !== $paren_start_position) {
            /* The string does not begin with parens, so do a normal parse
             * of the statement up to the first parenthesis. */
            $statement = self::extractStatement(
                $statements,
                $statement,
                $paren_start_position
            );

            /* Now the $statement starts of with a paren. */
            $paren_start_position = 0;
        }
        
        $paren_length = self::hasCharacter($statement, ')') + 1;

        /* Throws ParseException if nested parens found. */
        self::checkForNestedParenthesis(
            $statement,
            $paren_start_position,
            $paren_length
        );

        $extracted_paren = substr($statement, $paren_start_position, $paren_length);

        $statements[] = new Parenthesis($extracted_paren);

        return trim(substr($statement, $paren_length));
    }

    private static function extractStatement(
        array &$statements,
        string $whole_statement,
        int $ending_index,
        int $starting_index = 0
    ): string
    {
        $length = $ending_index - $starting_index;
        
        $extract = trim(substr($whole_statement, $starting_index, $length));

        $statements = array_merge($statements, explode(' ', $extract));

        if (strlen($whole_statement) === strlen($extract)) {
            return '';
        }
        
        $remaining_statement = trim(substr($whole_statement, $length));

        return $remaining_statement;
    }

    private static function parseStatementsIntoObjects(array &$statements): array
    {
        if (false !== self::hasCharacter($statements, ' ')) {
            foreach ($statements as $index => $statement) {
                if (is_string($statement)) {
                    $statements[$index] = explode(' ', $statement);
                }
            }
        }

        foreach ($statements as $index => $statement) {
            if (is_array($statement)) {
                $statement = self::parseStatementsIntoObjects($statement);
            }
            elseif (is_numeric($statement)) {
                $statement = new Number($statement);
            }
            elseif (in_array($statement, array_keys(self::FUNCTORS))) {
                $functor = self::FUNCTORS[$statement];
                $statement = new $functor();
            }
            elseif (empty($statement)) {
                unset($statements[$index]);
                continue;
            }
            elseif ($statement instanceof Parenthesis) {
                $statement = $statement->toFactors();
            }
            else {
                $statement = new Variable($statement);
            }

            $statements[$index] = $statement;
        }

        return $statements;
    }

    /**
     * @param array|string $statements
     * @param string $character
     * @return bool|int int is starting index of character
     */
    private static function hasCharacter($statements, string $character)
    {
        if (is_string($statements)) {
            $statements = [$statements];
        }

        foreach ($statements as $statement) {
            if (is_array($statement)) {
                $sub_has_character = self::hasCharacter($statement, $character);

                if (false !== $sub_has_character) {
                    return $sub_has_character;
                }
            }
            elseif (
                is_string($statement) &&
                ($position = strpos($statement, $character)) !== false
            ) {
                return $position;
            }
        }

        return false;
    }
    
    private static function checkForNestedParenthesis(
        string $statement,
        int $paren_start_position,
        int $paren_length
    ): void
    {
        $paren_statement = substr(
            $statement,
            $paren_start_position + 1,
            $paren_length
        );

        if (strpos($paren_statement, '(') !== false) {
            throw new ParseException(
                'Currently cannot parse nested parentheses'
            );
        }
    }
}
