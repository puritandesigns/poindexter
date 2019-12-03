<?php

namespace Poindexter\Interfaces;

interface FactorModelInterface
{
    /** @return int|float|string */
    public function getValue();

    /** @return string Values: int|float */
    public function getResultType();

    /** @return bool */
    public function isParenthesis();

    /** @return bool */
    public function isParenthesisOpen();

    /** @return bool */
    public function isParenthesisClose();

    /** @return bool */
    public function isVariable();

    /** @return bool */
    public function isNumber();

    /** @return bool */
    public function isFunctor();

    /**
     * Returns true if the supplied type matches this type
     * @param string $factor_type Either float or integer
     * @return bool */
    public function isType($factor_type);

    /** @return string */
    public function getType();
}
