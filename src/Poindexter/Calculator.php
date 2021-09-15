<?php

namespace Poindexter;

use Poindexter\Exceptions\InvalidReturnTypeException;
use Poindexter\Factors\Add;
use Poindexter\Factors\Divide;
use Poindexter\Factors\Multiply;
use Poindexter\Factors\Number;
use Poindexter\Factors\Parenthesis;
use Poindexter\Factors\Subtract;
use Poindexter\Factors\Variable;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\FactorModelInterface;
use Poindexter\Interfaces\ResultInterface;

final class Calculator
{
    /** @var array|\Poindexter\Interfaces\FactorModelInterface[] */
    private $factors;
    /** @var string */
    private $return_type;

    /**
     * Calculator constructor.
     * @param \Poindexter\Interfaces\FactorModelInterface[]|array $factors
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
     */
    public function calculate(array $data = null)
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
            /** @var \Poindexter\Factors\Variable $factor */
            $result = $factor->calculate($result, null, $data);
        }

        elseif (null !== $result && $factor->isNumber()) {
            echo 'this should not be possible'; exit;
            /** @var \Poindexter\Factors\Number $factor */
            $result = $factor->calculate($result);
        }

        elseif ($factor->isNumber()) {
            /** @var \Poindexter\Factors\Number $factor */
            $result = $factor->calculate($result);
        }

        elseif ($factor->isFunctor()) {
            /** @var \Poindexter\Factors\AbstractFunctor $factor */
            $second = next($statements);

            $second->preCalculate($data);

            $result = $factor->calculate($result, $second, $data);
        }

        elseif ($factor->isComparator()) {
            /** @var \Poindexter\Factors\AbstractFunctor $factor */
            $second = next($statements);

            $second->preCalculate($data);

            $result = $factor->calculate($result, $second, $data);
        }

        elseif ($factor->isParenthesis()) {
            $result = $factor->calculate($result);
        }

        $factor = next($statements);

        return $this->calculateFactor($factor, $statements, $result, $data);
    }

    /**
     * @param \Poindexter\Interfaces\FactorModelInterface[] $factors
     * @param \Poindexter\Factors|\Poindexter\Factors\Parenthesis|array $statements
     * @return \Poindexter\Factors
     */
    private function parseStatements(array $factors, $statements = [])
    {
        if (empty($factors)) {
            return $statements;
        }
        
        $factors = array_merge($factors);
        
        $factor = current($factors);
        
        $this->parseStatement($factor, $factors, 0, $statements);

        return $this->parseStatements($factors, $statements);
    }

    /**
     * @param \Poindexter\Interfaces\FactorModelInterface $factor
     * @param \Poindexter\Interfaces\FactorModelInterface[] $factors
     * @param int $index
     * @param \Poindexter\Factors|\Poindexter\Factors\Parenthesis|array $statements
     * @return int|null
     */
    private function parseStatement(
        $factor,
        &$factors,
        $index,
        &$statements = []
    )
    {
        if (false === $factor) {
            return null;
        }

        $statements[] = $this->forgeTypeObject($factor);

        unset($factors[$index]);

        return null;
    }

    /**
     * @param \Poindexter\Interfaces\FactorModelInterface[] $factors
     * @return int|null
     */
    private function getParenthesisCloseIndex(array &$factors)
    {
        $reversed = array_reverse($factors, true);

        foreach ($reversed as $index => $item) {
            if ($item->isParenthesisClose()) {
                return $index;
            }
        }
        
        return null;
    }

    /**
     * @param \Poindexter\Interfaces\FactorModelInterface|Parenthesis $type
     * @return \Poindexter\Interfaces\FactorInterface
     */
    private function forgeTypeObject($type)
    {
        if (! $type instanceof FactorInterface) {
            return $this->forgeFunctorObject($type);
        }

        return $type;
    }

    private function forgeFunctorObject(FactorInterface $type)
    {
        if ($type->isType('add')) {
            return new Add();
        }

        if ($type->isType('subtract')) {
            return new Subtract();
        }

        if ($type->isType('multiply')) {
            return new Multiply();
        }

        return new Divide();
    }
}
