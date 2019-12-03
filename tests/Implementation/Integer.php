<?php

namespace Implementation;

class Integer extends Number
{
    public function __construct(
        $number,
        array $data = []
    ) {
        parent::__construct($number, 'integer', $data);
    }
}
