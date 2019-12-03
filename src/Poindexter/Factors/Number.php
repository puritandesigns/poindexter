<?php

namespace Poindexter\Factors;

use Poindexter\Exceptions\InvalidResultParameterException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;
use Poindexter\Traits\DeterminesFactorType;

final class Number extends Result implements FactorInterface
{
    use DeterminesFactorType;

    public function calculate(
        ResultInterface $result = null,
        FactorInterface $next = null,
        array $data = []
    )
    {
        if (null !== $result) {
echo __FILE__ . ' on line ' . __LINE__;
echo '<pre style="background: white; width: 1000px;">' . PHP_EOL;
print_r([
    'this' => $this,
    'next' => $next,
    'result' => $result,
]);
echo PHP_EOL . '</pre>' . PHP_EOL;

            throw new InvalidResultParameterException(
                'A result object cannot be passed to a Number'
            );
        }

        if (null === $next) {
            return $this;
        }

        return $next->calculate($this);
    }

    protected function getType()
    {
        return 'number';
    }
}
