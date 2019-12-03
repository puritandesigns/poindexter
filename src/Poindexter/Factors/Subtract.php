<?php

namespace Poindexter\Factors;

use Poindexter\Interfaces\ResultInterface;

final class Subtract extends AbstractFunctor
{
    protected function doTheMath(
        ResultInterface $first,
        ResultInterface $second
    )
    {
        return $first->getValue() - $second->getValue();
    }

    protected function getType()
    {
        return 'subtract';
    }
}
