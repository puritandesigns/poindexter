<?php

namespace Poindexter\Factors;

class LessThanEquals extends LessThan
{
    public function __construct()
    {
        parent::__construct(true);
    }
}
