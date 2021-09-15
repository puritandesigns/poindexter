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
        array $data = null
    ): ResultInterface
    {
        if (null !== $result) {
            throw new InvalidResultParameterException(
                'A result object cannot be passed to a Number'
            );
        }

        if (null === $next) {
            return $this;
        }

        return $next->calculate($this);
    }

    public function getType(): string
    {
        return 'number';
    }

    public function preCalculate(array $data = null): void
    {

    }
}
