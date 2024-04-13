<?php
namespace Src\Element;
use Src\Element\Unit;

class N extends Unit {

    public function __construct($base, $exponent) {
        $i = 0;
        while($i < $exponent) {
            $this->factorial[] = $base;
            $i++;
        }
        $this->border = $i;
        $this->exponent[$base] = $i;
        $this->factorialL = count($this->factorial);
    }
}