<?php

namespace Implementation;

use Implemodel\Model;
use Poindexter\Exceptions\InvalidResultParameterException;
use Poindexter\Exceptions\VariableDataMissingException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;

class Variable extends Factor implements ResultInterface
{
    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = []
    )
    {
        if (null !== $result) {
            throw new InvalidResultParameterException(
                'A result object cannot be passed to a Number'
            );
        }

        $variable_name = $this->getData('variable_name');

        if (! isset($data[$variable_name])) {
            throw new VariableDataMissingException(
                'Data missing to replace variable, ' . $variable_name
            );
        }

        $this->setValue($data[$variable_name]);

        if (null === $next) {
            return $this;
        }

        return $next->calculate($this);
    }

    public function getValue($float_precision = 3)
    {
        return $this->getData('value');
    }

    public function setValue($value)
    {
        return $this->data['value'] = $value;
    }

    public function getReturnType()
    {
        return $this->getData('variable_type');
    }

    public function isFloat(): bool
    {
        return 'float' === $this->getReturnType();
    }
}
