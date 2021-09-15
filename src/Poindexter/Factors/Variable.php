<?php

namespace Poindexter\Factors;

use Poindexter\Exceptions\InvalidResultParameterException;
use Poindexter\Exceptions\VariableDataMissingException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;
use Poindexter\Traits\DeterminesFactorType;

final class Variable extends Result implements FactorInterface
{
    use DeterminesFactorType;

    private $variable_name;

    private $variable_compiled = false;

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
     * @param \Poindexter\Interfaces\ResultInterface|null $result
     * @param \Poindexter\Interfaces\FactorInterface|null $next
     * @param array|null $data
     * @return $this|\Poindexter\Interfaces\ResultInterface
     * @throws \Poindexter\Exceptions\InvalidResultParameterException
     * @throws \Poindexter\Exceptions\VariableDataMissingException
     */
    public function calculate(
        ResultInterface $result = null,
        FactorInterface $next = null,
        array $data = null
    ): ResultInterface
    {
        if (null !== $result) {
            throw new InvalidResultParameterException(
                'A result object cannot be passed to a variable'
            );
        }

        if (! $this->variable_compiled) {
            $this->compileVariable($data);
        }

        if (null === $next) {
            return $this;
        }

        return $next->calculate($this);
    }

    public function getType(): string
    {
        return 'variable';
    }

    public function preCalculate(array $data = null): void
    {
        $this->compileVariable($data ?? []);
    }

    private function compileVariable(array $data = [])
    {
        if (! isset($data[$this->variable_name])) {
            throw new VariableDataMissingException(
                'Data missing to replace variable, ' . $this->variable_name
            );
        }

        $this->setValue($data[$this->variable_name]);

        $this->variable_compiled = true;
    }
}
