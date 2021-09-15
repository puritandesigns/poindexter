<?php

namespace Poindexter\Factors;

class LessThan extends AbstractComparator
{
    protected function compare($first, $second): int
    {
        return (int) $first < $second;
    }

    public function getType(): string
    {
        return 'less_than';
    }
}
