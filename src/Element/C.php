<?php
namespace Src\Element;

use Src\Element\Unit;

class C extends Unit{

    public function __construct(int $a, int $b) {
        if($a < $b) return false;
        if($b > $a - $b) $max = $b;
        else $max = $a - $b;

        $count =0;
        for($i = $a; $i> $max; $i--) {
            $this->exponent[$i] = 1;
            $count ++;
        }
        for($i = $max; $i >0 ; $i--) {
            $this->exponent[$i] = 0;
        }
        $this->exponent[0] = $count;
        for($i=$a -$max; $i >0; $i--) {
            $this->exponent[$i] = -1;
        }
        // $this->exponent[0] += $a -$max;
        $this->factorial[] = $a;
        $this->factorial[] = $max;
        $this->factorial[] = $a - $max;
        $this->border = 1;
        $this->factorialL = count($this->factorial);
    }
    public function string() {
        $a = $this->factorial[0];
        $b = $this->factorial[2];
        return "C({$a},{$b})";
    }
}