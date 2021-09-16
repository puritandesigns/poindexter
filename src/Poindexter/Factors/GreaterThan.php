<?php

namespace Poindexter\Factors;

class GreaterThan extends AbstractComparator
{
    protected function compare($first, $second): int
    {
        return (int) ($first > $second);
    }

    public function getType(): string
    {
        return 'greater_than';
    }
}
