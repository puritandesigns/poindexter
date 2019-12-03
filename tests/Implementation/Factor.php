<?php

namespace Implementation;

use Implemodel\Model;
use Poindexter\Exceptions\InvalidResultParameterException;
use Poindexter\Exceptions\VariableDataMissingException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\FactorModelInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Traits\DeterminesFactorType;

class Factor extends Model implements FactorModelInterface
{
    use DeterminesFactorType;

    protected $table = 'calculator_factors';

    protected $columns = [
        'id' => null,
        'calculator_id' => 1,
        'type' => '',
        'value' => null,
        'variable_type' => 'float',
        'sort' => 1,
    ];

    public function getValue()
    {
        return $this->data['value'];
    }

    public function getResultType()
    {
        return $this->data['variable_type'];
    }

    public function getType()
    {
        return $this->data['type'];
    }
}
