<?php

namespace Poindexter\Interfaces;

interface FactorInterface
{
    /**
     * @param \Poindexter\Interfaces\ResultInterface $result
     * @param \Poindexter\Interfaces\FactorInterface|null $next
     * @param array $data Data passed to help fill in variables
     * @return \Poindexter\Interfaces\ResultInterface
     */
    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = []
    );
}
