<?php

namespace Poindexter;

use Poindexter\Exceptions\InvalidMethodCallException;
use Poindexter\Interfaces\FactorInterface;

final class Factors implements \ArrayAccess, \Countable, \Iterator
{
    /** @var \Poindexter\Interfaces\FactorInterface[] */
    private $factors;
    /** @var int */
    private $index = 0;

    /**
     * Factors constructor.
     * @param \Poindexter\Interfaces\FactorInterface[] $factors
     */
    public function __construct(array $factors = [])
    {
        $this->factors = $factors;
    }

    public function addFactor(FactorInterface $factor)
    {
        $this->factors[] = $factor;
    }

    public function count()
    {
        return count($this->factors);
    }

    public function current()
    {
        return $this->factors[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index++;
    }

    public function valid()
    {
        return isset($this->factors[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function offsetExists($offset)
    {
        return isset($this->factors[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->factors[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new InvalidMethodCallException(
            'Use addFactor method instead'
        );
    }

    public function offsetUnset($offset)
    {
        throw new InvalidMethodCallException(
            'Should not need to unset a factor'
        );
    }
}
