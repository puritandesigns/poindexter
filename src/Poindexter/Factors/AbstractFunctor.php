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
     * @param array $data
     * @return \Poindexter\Interfaces\ResultInterface|\Poindexter\Result
     * @throws \Poindexter\Exceptions\InvalidFactorParameterException
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     */
    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = []
    )
    {
echo __FILE__ . ' on line ' . __LINE__;
echo '<pre style="background: white; width: 1000px;">' . PHP_EOL;
print_r([
    'this' => $this,
    'result' => $result,
    'next' => $next,
]);
echo PHP_EOL . '</pre>' . PHP_EOL;


        if (! ($next instanceof ResultInterface)) {
            throw new InvalidFactorParameterException(
                ''
            );
        }

        $number = $this->doTheMath($result, $next);

        $return_type = 'integer';
        if ($result->isFloat() || $next->isFloat()) {
            $return_type = 'float';
        }

        return new Result($number, $return_type);
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
