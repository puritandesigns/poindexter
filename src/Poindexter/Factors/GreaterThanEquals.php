<?php

namespace Poindexter\Factors;

class GreaterThanEquals extends GreaterThan
{
    public function __construct()
    {
        parent::__construct(true);
    }
}
