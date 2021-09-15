<?php

namespace Poindexter\Traits;

trait DeterminesFactorType
{
    public function isParenthesis(): bool
    {
        return $this->isType('parenthesis');
    }

//    /** @return bool */
//    public function isParenthesisOpen(): bool
//    {
//        return $this->isType('parenthesis_open');
//    }
//
//    /** @return bool */
//    public function isParenthesisClose(): bool
//    {
//        return $this->isType('parenthesis_close');
//    }

    public function isVariable(): bool
    {
        return $this->isType('variable');
    }

    public function isNumber(): bool
    {
        return $this->isType('number');
    }

    public function isComparator(): bool
    {
        return in_array(
            $this->getType(),
            ['greater_than', 'less_than', 'equals']
        );
    }

    public function isFunctor(): bool
    {
        return in_array(
            $this->getType(),
            ['add', 'subtract', 'multiply', 'divide']
        );
    }

    public function isType(string $factor_type): bool
    {
        return $factor_type === $this->getType();
    }

    abstract public function getType(): string;
}
