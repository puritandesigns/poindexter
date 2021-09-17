<?php

namespace Poindexter\Factors;

use Poindexter\Traits\DeterminesFactorType;

class LogicalAnd extends AbstractComparator
{
    use DeterminesFactorType;

    protected function compare($first, $second): int
    {
        if ($first && $second) {
            return 1;
        }

        return 0;
    }

    public function getType(): string
    {
        return 'and';
    }
}
