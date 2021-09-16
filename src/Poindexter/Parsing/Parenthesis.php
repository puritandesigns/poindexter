<?php

namespace Poindexter\Parsing;

use Poindexter\Parsing\Parser;

class Parenthesis
{
    private $statement;

    private $factors;

    public function __construct(string $statement)
    {
        $statement = ltrim(trim($statement), '(');

        if (')' === substr($statement, -1)) {
            $statement = substr(
                $statement,
                0,
                strlen($statement) - 1
            );
        }

        $this->statement = trim($statement);
    }

    public function subParse(): void
    {
        $this->factors = new \Poindexter\Factors\Parenthesis(
            Parser::parse($this->statement)
        );
    }

    public function toFactors(): \Poindexter\Factors\Parenthesis
    {
        if (empty($this->factors)) {
            $this->subParse();
        }

        return $this->factors;
    }
}
