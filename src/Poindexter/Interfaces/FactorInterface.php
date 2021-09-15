<?php

namespace Poindexter\Interfaces;

interface FactorInterface
{
    /**
     * @param \Poindexter\Interfaces\ResultInterface $result
     * @param \Poindexter\Interfaces\FactorInterface|null $next
     * @param array|null $data Data passed to help fill in variables
     * @return \Poindexter\Interfaces\ResultInterface
     */
    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = null
    ): ResultInterface;

    public function getType(): string;

    public function isType(string $factor_type): bool;

    public function isComparator(): bool;

    public function isFunctor(): bool;

    public function isNumber(): bool;

    public function isParenthesis(): bool;

    public function isVariable(): bool;

    public function preCalculate(array $data = null): void;
}
