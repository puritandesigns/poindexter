<?php

namespace Poindexter\Factors;

use Poindexter\Exceptions\InvalidResultParameterException;
use Poindexter\Exceptions\VariableDataMissingException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;

final class Variable extends Result implements FactorInterface
{
    private $variable_name;

    /**
     * Variable constructor.
     * @param string $variable_name
     * @param string $return_type
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     */
    public function __construct($variable_name, $return_type = 'float')
    {
        $this->variable_name = $variable_name;

        parent::__construct(0, $return_type);
    }

    /**
     * @param \Poindexter\Interfaces\ResultInterface $result
     * @param \Poindexter\Interfaces\FactorInterface|null $next
     * @param array $data
     * @return $this|\Poindexter\Interfaces\ResultInterface
     * @throws \Poindexter\Exceptions\InvalidResultParameterException
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     * @throws \Poindexter\Exceptions\VariableDataMissingException
     */
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

        if (! isset($data[$this->variable_name])) {
            throw new VariableDataMissingException(
                'Data missing to replace variable, ' . $this->variable_name
            );
        }

        $this->setValue($data[$this->variable_name]);

        if (null === $next) {
            return $this;
        }

        return $next->calculate($this);
    }

    protected function getType()
    {
        return 'variable';
    }
}
