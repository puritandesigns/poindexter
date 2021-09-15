<?php

namespace Poindexter\Interfaces;

interface ResultInterface
{
    /** @var string Float return type */
    public const FLOAT = 'float';

    /** @var string Integer return type */
    public const INTEGER = 'integer';

    /**
     * @param int $float_precision
     * @return int|float
     */
    public function getValue($float_precision = 3);

    public function getResultType(): string;

    public function isFloat(): bool;
}
