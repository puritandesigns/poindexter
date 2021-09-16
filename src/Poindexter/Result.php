<?php

namespace Poindexter;

use Poindexter\Exceptions\InvalidReturnTypeException;
use Poindexter\Interfaces\ResultInterface;

class Result implements ResultInterface
{
    /** @var float|int */
    private $value;
    /** @var string */
    private $return_type;

    /**
     * Result constructor.
     * @param int|float $value
     * @param string $return_type Must be either integer or float
     * @throws \Poindexter\Exceptions\InvalidReturnTypeException
     */
    public function __construct($value, string $return_type = 'float')
    {
        $this->value = $value;

        if (
            ResultInterface::FLOAT !== $return_type &&
            ResultInterface::INTEGER !== $return_type
        ) {
            throw new InvalidReturnTypeException(
                'Result return type must be an integer or a float, ' .
                $return_type . ' given instead.'
            );
        }

        $this->return_type = $return_type;
    }

    public function getValue(int $float_precision = 3)
    {
        if ($this->isFloat()) {
            return round($this->value, $float_precision);
        }

        return (int) $this->value;
    }

    public function setValue($value)
    {
        if (! is_int($value) && ! is_float($value)) {
            throw new InvalidReturnTypeException(
                'The value to set on a result must be an integer or float'
            );
        }

        $this->value = $value;
    }

    public function getResultType(): string
    {
        return $this->return_type;
    }

    public function isFloat(): bool
    {
        return ResultInterface::FLOAT === $this->getResultType();
    }
}
