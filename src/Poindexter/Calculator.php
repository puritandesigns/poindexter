<?php

namespace Poindexter;

use Poindexter\Exceptions\InvalidReturnTypeException;
use Poindexter\Exceptions\ParseException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;

final class Calculator
{
    /** @var array|\Poindexter\Interfaces\FactorInterface[] */
    private $factors;
    /** @var string */
    private $return_type;

    /**
     * Calculator constructor.
     * @param \Poindexter\Interfaces\FactorInterface[]|array $factors
     * @param string $return_type
     */
    public function __construct(array $factors, $return_type = 'float')
    {
        $this->factors = $factors;

        if (
            ResultInterface::FLOAT !== $return_type &&
            ResultInterface::INTEGER !== $return_type
        ) {
            throw new InvalidReturnTypeException(
                'Return type must be an integer or a float'
            );
        }

        $this->return_type = $return_type;
    }

    /**
     * @param array|null $data
     * @return ResultInterface
     * @throws \Poindexter\Exceptions\InvalidFactorParameterException
     * @throws \Poindexter\Exceptions\InvalidResultParameterException
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     * @throws \Poindexter\Exceptions\ParseException
     * @throws \Poindexter\Exceptions\VariableDataMissingException
     */
    public function calculate(array $data = null): ResultInterface
    {
        $statements = $this->factors;

        $factor = current($statements);

        $result = $this->calculateFactor(
            $factor,
            $statements,
            null,
            $data
        );

        return new Result($result->getValue(), $this->return_type);
    }

    public static function calculateInt(array $factors, array $data = null): int
    {
        $result = self::calculateResult(
            $factors,
            $data,
            ResultInterface::INTEGER
        );

        return $result->getValue();
    }

    public static function calculateFloat(array $factors, array $data = null): float
    {
        $result = self::calculateResult(
            $factors,
            $data
        );

        return $result->getValue();
    }

    /**
     * @param FactorInterface[] $factors
     * @param array|null $data
     * @param string $result_type
     * @return \Poindexter\Interfaces\ResultInterface
     */
    public static function calculateResult(
        $factors,
        array $data = null,
        string $result_type = 'float'
    ): ResultInterface
    {
        $calculator = new self($factors, $result_type);

        return $calculator->calculate($data);
    }

    /**
     * @param \Poindexter\Interfaces\FactorInterface|false $factor
     * @param FactorInterface[] $statements
     * @param ResultInterface|null $result
     * @param array|null $data
     * @return ResultInterface
     * @throws \Poindexter\Exceptions\InvalidFactorParameterException
     * @throws \Poindexter\Exceptions\InvalidResultParameterException
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     * @throws \Poindexter\Exceptions\ParseException
     * @throws \Poindexter\Exceptions\VariableDataMissingException
     */
    private function calculateFactor(
        $factor,
        &$statements,
        $result = null,
        array $data = null
    )
    {
        if (! $factor) {
            return $result;
        }

        $factor->preCalculate($data);

        if ($factor->isVariable()) {
            $result = $factor->calculate($result, null, $data);
        }
        elseif (null !== $result && $factor->isNumber()) {
            /* This should not be possible.
             * There's a number/var/paren next to another number/var/paren
             * without a functor or comparator between them. */
            throw new ParseException(
                'Check your formula for statements without any operations'
            );
        }
        elseif ($factor->isNumber() || $factor->isParenthesis()) {
            $result = $factor->calculate($result);
        }
        elseif ($factor->isFunctor() || $factor->isComparator()) {
            $second = next($statements);

            $second->preCalculate($data);

            $result = $factor->calculate($result, $second, $data);
        }

        $factor = next($statements);

        return $this->calculateFactor($factor, $statements, $result, $data);
    }
}
