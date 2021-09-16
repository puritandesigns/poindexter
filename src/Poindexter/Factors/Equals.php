<?php

namespace Poindexter\Factors;

class Equals extends AbstractComparator
{
    protected function compare($first, $second): int
    {
        return (int) ($first == $second);
    }

    public function getType(): string
    {
        return 'equals';
    }
}
