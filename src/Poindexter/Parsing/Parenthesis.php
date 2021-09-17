<?php

namespace Poindexter\Parsing;

use Poindexter\Exceptions\ParseException;

class Parenthesis
{
    /** @var string */
    private $statement;
    /** @var \Poindexter\Factors\Parenthesis */
    private $factors;

    /**
     * Parenthesis constructor.
     * @param string|\Poindexter\Interfaces\FactorInterface[] $statement_or_factors
     */
    public function __construct($statement_or_factors)
    {
        if (is_string($statement_or_factors)) {
            $statement = ltrim(trim($statement_or_factors), '(');

            if (')' === substr($statement, -1)) {
                $statement = substr(
                    $statement,
                    0,
                    strlen($statement) - 1
                );
            }

            $this->statement = trim($statement);
        } else {
            $this->factors = new \Poindexter\Factors\Parenthesis(
                $statement_or_factors
            );
        }
    }

    public function subParse(): void
    {
        if (null !== $this->statement && null === $this->factors) {
            $this->factors = new \Poindexter\Factors\Parenthesis(
                Parser::parse($this->statement)
            );
        }
    }

    public function toFactors(): \Poindexter\Factors\Parenthesis
    {
        if (null === $this->factors) {
            if (null === $this->statement) {
                throw new ParseException(
                    'You cannot create a Parenthesis from an empty statement'
                );
            }

            $this->subParse();
        }

        return $this->factors;
    }
}
