<?php

namespace Poindexter\Factors;

use Poindexter\Calculator;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;
use Poindexter\Traits\DeterminesFactorType;

class Parenthesis extends Result implements FactorInterface
{
    /** @var FactorInterface[] */
    private $factors;

    use DeterminesFactorType;

    /**
     * Parenthesis constructor.
     * @param FactorInterface[] $factors
     * @param string $return_type
     */
    public function __construct($factors = [], string $return_type = 'float')
    {
        $this->factors = $factors;

        parent::__construct(null, $return_type);
    }

    public function calculate(
        ResultInterface $outer_result = null,
        FactorInterface $outer_next = null,
        array $data = null
    ): ResultInterface
    {
        if (empty($this->getValue())) {
            $this->calculateParenthesisValue($data);
        }

        return $this;
    }

    public function getType(): string
    {
        return 'parenthesis';
    }

    public function preCalculate(array $data = null): void
    {
        if (empty($this->getValue())) {
            $this->calculateParenthesisValue($data);
        }
    }

    private function calculateParenthesisValue(array $data = null)
    {
        $result = Calculator::calculateResult(
            $this->factors,
            $data,
            $this->getResultType()
        );

        $this->setValue($result->getValue());
    }
}
