<?php

namespace Poindexter\Factors;

use Poindexter\Exceptions\DivideByZeroException;
use Poindexter\Interfaces\ResultInterface;

final class Divide extends AbstractFunctor
{
    protected function doTheMath(
        ResultInterface $first,
        ResultInterface $second
    )
    {
        if (0 === $second->getValue()) {
            throw new DivideByZeroException('Cannot divide by zero');
        }

        return $first->getValue() / $second->getValue();
    }

    public function getType(): string
    {
        return 'divide';
    }
}
