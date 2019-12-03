<?php

namespace Poindexter\Interfaces;

interface ResultInterface
{
    /** @var string Float return type */
    const FLOAT = 'float';
    /** @var string Integer return type */
    const INTEGER = 'integer';

    /**
     * @param int $float_precision
     * @return int|float
     */
    public function getValue($float_precision = 3);

    /**
     * @return string
     */
    public function getReturnType();

    /**
     * @return bool
     */
    public function isFloat();
}
