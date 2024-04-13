<?php
namespace Src\Element;

use Src\Element\Unit;

class P extends Unit{

    public function __construct(int $a, int $b) {
        if($a < $b) return false;
        $count =0;
        for($i = $a; $i> $b; $i--) {
            $this->exponent[$i] = 1;
            $count ++;
        }
        for($i = $b; $i >0 ; $i--) {
            $this->exponent[$i] = 0;
        }
        // $this->top[0] = $count;
        $this->factorial[] = $a;
        $this->factorial[] = $b;
        $this->border = 1;
        $this->factorialL = count($this->factorial);

    }
    public function string() {
        $a =  $this->factorial[0];
        $b = $this->factorial[1];
        return "P({$a},{$b})";
    }
}