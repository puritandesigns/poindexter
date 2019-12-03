<?php

namespace Poindexter\Factors;

use Poindexter\Factors;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Traits\DeterminesFactorType;

class Parenthesis implements FactorInterface, \ArrayAccess
{
    /** @var \Poindexter\Factors */
    private $factors;

    use DeterminesFactorType;

    /**
     * Parenthesis constructor.
     * @param \Poindexter\Factors|array $factors
     */
    public function __construct($factors = [])
    {
        if (is_array($factors)) {
            $factors = new Factors($factors);
        }

        $this->factors = $factors;
    }

    public function calculate(
        ResultInterface $outer_result = null,
        FactorInterface $outer_next = null,
        array $data = []
    )
    {
echo __FILE__ . ' on line ' . __LINE__;
echo '<pre style="background: white; width: 1000px;">' . PHP_EOL;
print_r(compact('outer_next', 'outer_result'));
echo PHP_EOL . '</pre>' . PHP_EOL;
        $i = 0;
        $count = count($this->factors);

        $inner_result = null;
        while ($i < $count) {
            $current = $this->factors[$i];

            $i++;

            $next = null;
            if (isset($this->factors[$i])) {
                $next = $this->factors[$i];
            }

            $i++;

            $inner_result = $current->calculate($inner_result, $next, $data);
        }

        if (null === $outer_next) {
            return $inner_result;
        }

        return $outer_next->calculate($inner_result, $outer_result, $data);
    }

    protected function getType()
    {
        return 'parenthesis';
    }

    public function addFactor(FactorInterface $factor)
    {
        $this->factors->addFactor($factor);
    }

    public function offsetExists($offset)
    {
        return $this->factors->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->factors->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->factors->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->factors->offsetUnset($offset);
    }
}
