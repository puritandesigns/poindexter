<?php

namespace Poindexter\Factors;

use Poindexter\Exceptions\InvalidFactorParameterException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;
use Poindexter\Traits\DeterminesFactorType;

abstract class AbstractFunctor implements FactorInterface
{
    use DeterminesFactorType;

    /**
     * @param \Poindexter\Interfaces\ResultInterface $result
     * @param \Poindexter\Interfaces\FactorInterface|null $next
     * @param array|null $data
     * @return \Poindexter\Interfaces\ResultInterface|\Poindexter\Result
     * @throws \Poindexter\Exceptions\InvalidFactorParameterException
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     */
    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = null
    ): ResultInterface
    {
        if (! ($next instanceof ResultInterface)) {
            throw new InvalidFactorParameterException(
                'Expecting $next to be a ResultInterface'
            );
        }

        $number = $this->doTheMath($result, $next);

        $return_type = ResultInterface::INTEGER;
        if ($result->isFloat() || $next->isFloat()) {
            $return_type = ResultInterface::FLOAT;
        }

        return new Result($number, $return_type);
    }

    public function preCalculate(array $data = null): void
    {

    }

    /**
     * @param \Poindexter\Interfaces\ResultInterface $first
     * @param \Poindexter\Interfaces\ResultInterface $second
     * @return int|float
     */
    abstract protected function doTheMath(
        ResultInterface $first,
        ResultInterface $second
    );
}
