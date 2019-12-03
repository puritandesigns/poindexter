<?php

namespace Poindexter\Traits;

trait DeterminesFactorType
{
    /** @return bool */
    public function isParenthesis()
    {
        return $this->isType('parenthesis');
    }

    /** @return bool */
    public function isParenthesisOpen()
    {
        return $this->isType('parenthesis_open');
    }

    /** @return bool */
    public function isParenthesisClose()
    {
        return $this->isType('parenthesis_close');
    }

    /** @return bool */
    public function isVariable()
    {
        return $this->isType('variable');
    }

    /** @return bool */
    public function isNumber()
    {
        return $this->isType('number');
    }

    /** @return bool */
    public function isFunctor()
    {
        return in_array(
            $this->getType(),
            ['add', 'subtract', 'multiply', 'divide']
        );
    }

    /**
     * Returns true if the supplied type matches this type
     * @param string $factor_type Either float or integer
     * @return bool */
    public function isType($factor_type)
    {
        return $factor_type === $this->getType();
    }

    abstract protected function getType();
}
