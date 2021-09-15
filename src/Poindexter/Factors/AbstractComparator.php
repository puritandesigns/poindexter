<?php

namespace Poindexter\Factors;

use Poindexter\Exceptions\InvalidFactorParameterException;
use Poindexter\Interfaces\FactorInterface;
use Poindexter\Interfaces\ResultInterface;
use Poindexter\Result;
use Poindexter\Traits\DeterminesFactorType;

abstract class AbstractComparator implements FactorInterface
{
    use DeterminesFactorType;

    /** @var bool */
    private $allow_equal_to;

    public function __construct(bool $allow_equal_to = false)
    {
        $this->allow_equal_to = $allow_equal_to;
    }

    public function calculate(
        ResultInterface $result,
        FactorInterface $next = null,
        array $data = null
    ): ResultInterface
    {
        if (! ($next instanceof ResultInterface)) {
            throw new InvalidFactorParameterException(
                "Trying to compare {$result->getResultType()} and {$next->getType()}"
            );
        }

        return new Result(
            $this->doTheMath($result, $next),
            ResultInterface::INTEGER
        );
    }

    public function preCalculate(array $data = null): void
    {

    }

    protected function doTheMath(
        ResultInterface $first,
        ResultInterface $second
    ): int
    {
        $first_value = $first->getValue();
        $second_value = $second->getValue();

        if ($this->allow_equal_to && ($first_value == $second_value)) {
            return 1;
        }

        return $this->compare($first_value, $second_value);
    }

    /**
     * @param float|int $first
     * @param float|int $second
     * @return int 1 if matches comparator, 0 if does not match
     */
    abstract protected function compare($first, $second): int;
}
