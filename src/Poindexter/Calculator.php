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
        $statements = $this->parseStatements($this->factors);

        $factor = current($statements);

        $result = $this->calculateFactor(
            $factor,
            $statements,
            null,
            $data
        );

        return new Result($result->getValue(), $this->return_type);
    }

    /**
     * @param \Poindexter\Interfaces\FactorModelInterface|false $factor
     * @param Factors $statements
     * @param ResultInterface|null $result
     * @param array $data
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
echo 'START OF calculateFactor FUNCTION ' . __FILE__ . ' on line ' . __LINE__ . PHP_EOL;
print_r($factor);
echo PHP_EOL;

        if ($factor->isVariable()) {
            /** @var \Poindexter\Factors\Variable $factor */
            $result = $factor->calculate($result, null, $data);

//            $factor = next($statements);
        }

        elseif (null !== $result && $factor->isNumber()) {
            echo 'this should not be possible'; exit;
            /** @var \Poindexter\Factors\Number $factor */
            $result = $factor->calculate($result);

//            $factor = next($statements);
        }

        elseif ($factor->isNumber()) {
            /** @var \Poindexter\Factors\Number $factor */
            $result = $factor->calculate($result);

//            $factor = next($statements);
        }

        elseif ($factor->isFunctor()) {
            /** @var \Poindexter\Factors\AbstractFunctor $factor */
            $second = next($statements);

            $result = $factor->calculate($result, $second, $data);

//            $factor = next($statements);
        }

        elseif ($factor->isParenthesis()) {
            $result = $factor->calculate($result);

//            $factor = next($statements);
        }

        $factor = next($statements);

echo 'END OF calculateFactor FUNCTION ' . __FILE__ . ' on line ' . __LINE__ . PHP_EOL;
print_r($factor);
print_r($result);
echo PHP_EOL;
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
        if (false === $factor || $factor->isParenthesisClose()) {
            return null;
        }

        if ($factor->isParenthesisOpen()) {
            $length = $this->getParenthesisCloseIndex($factors) - $index;

            unset($factors[$index], $factors[$length]);

            $subfactors = array_slice($factors, 0, $length - 1);

            foreach (range($index + 1, $length - 1) as $item) {
                unset($factors[$item]);
            }

            $statements[] = new Parenthesis($this->parseStatements($subfactors));

            return $length;
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
        if ($type->isNumber()) {
            return new Number($type->getValue(), $type->getResultType());
        }

        if ($type->isVariable()) {
            return new Variable($type->getValue(), $type->getResultType());
        }

        if ($type->isFunctor()) {
            return $this->forgeFunctorObject($type);
        }

        return $type;
    }

    private function forgeFunctorObject(FactorModelInterface $type)
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
