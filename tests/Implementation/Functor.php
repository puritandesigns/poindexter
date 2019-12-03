<?php

namespace Implementation;

use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;

class Functor extends Factor
{
    public function __construct($functor_type, array $data = [])
    {
        parent::__construct(array_merge($data, [
            'type' => $functor_type,
        ]));
    }

    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = []
    )
    {
        $type = $this->getData('type');

        $number = 0;

        if ('add' === $type) {
            $number = $result->getValue() + $next->getValue();
        } elseif ('subtract' === $type) {
            $number = $result->getValue() - $next->getValue();
        } elseif ('multiply' === $type) {
            $number = $result->getValue() * $next->getValue();
        } elseif ('divide' === $type) {
            $number = $result->getValue() / $next->getValue();
        }

        return new Result($number, $this->getData('variable_type'));
    }
}
