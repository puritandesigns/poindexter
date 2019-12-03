<?php

namespace Implementation;

use Poindexter\Interfaces\ResultInterface;

class Number extends Factor implements ResultInterface
{
    public function __construct($number, $return_type = 'float', array $data = [])
    {
        parent::__construct(array_merge($data, [
            'type' => 'number',
            'variable_type' => $return_type,
            'value' => $number
        ]));
    }

    public function getValue($float_precision = 3)
    {
        if ($this->isFloat()) {
            return round($this->data['value'], $float_precision);
        }

        return (int) $this->data['value'];
    }

    public function getReturnType()
    {
        return $this->getData('variable_type');
    }

    public function isFloat(): bool
    {
        return 'float' === $this->getData('variable_type');
    }
}
