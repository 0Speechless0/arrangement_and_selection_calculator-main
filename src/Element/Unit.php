<?php

namespace Src\Element;

class Unit {
    protected $exponent= array();
    protected $factorial = array();
    protected $border = 1;
    protected $factorialL;
    // protected $bottom= array();
    protected ?Sigma $sigma = null;
    public function __construct($factorial, $border) {
        $this->factorialL= count($factorial);
        $sectionA = array_slice($factorial,0, $border);
        $sectionB = array_slice($factorial, $border);
        sort($sectionA);
        sort($sectionB);
        $factorial =  array_merge($sectionA, $sectionB);
        $this->factorial = $factorial;
        $this->border = $border;

        for($i=0; $i <= $factorial[0]; $i++) $this->exponent[$i] = 0;
        $i = 0;
        while($i < $this->factorialL ) {
            $num = $factorial[$i];

            while($num > 0) {
                if($i < $border) {
                    $this->exponent[$num] +=1;
                }
                else $this->exponent[$num] -=1;
                $num --;
            }
            $i++;
        }
    }
    public function muti(Unit $u) {
        $anotherE = $u->getExponent();
        $this->mergeUnit($u->getFactorial(), $u->getSegima(), $u->getBorder() );
        $max = max(count($anotherE ), count($this->exponent));
        for($i = 1; $i <= $max; $i++) {
            if($this->exponent[$i] ) {
                $this->exponent[$i] += $anotherE[$i];
            }
            else $this->exponent[$i] = $anotherE[$i];
        }
        return $this;
    }

    public function getExponent() {
        return $this->exponent;
    }
    public function getFactorial() {
        return $this->factorial;
    }
    public function getBorder() {
        return $this->border;
    }
    private function mergeUnit($factorial, $sigma, $border) {
        $s1 = array_slice($this->factorial, 0, $this->border);
        $s2 = array_slice($this->factorial, $this->border);
        $s1 = array_merge($s1, array_slice($factorial, 0, $border));
        $s2 = array_merge($s2, array_slice($factorial, $border));
        $this->factorial = array_merge($s1, $s2);
        $this->border = count($s1);
        if($sigma) {
            $this->mergeSigma($sigma);
            do{
                for($i=0; $i < $this->border ; $i++) $arr1[$i] = 0;
                for($i=0; $i < $factorialL - $this->border ; $i++) $arr2[$i] = 0;
                $s1 = arary_slice($sigma->amounts, 0, $border);
                $s1 = array_merge($arr1, $s1);
                $s2 = arary_slice($sigma->amounts, $border);
                $s2 = array_merge($arr2, $s2);
                $sigma->amounts = array_merge($s1, $s2);
            }while($sigma = $sigma->nextLevel);      
            
        }
    }
    public function calculate() {
        $num = new number('1');
        if($this->sigma) {
            return $this->sigma($this->sigma, $this->factorial);
        }
        $exponentL = count($this->exponent);
        // $i = 0;
        // while($i < $this->factorial_count) {
        //     $num = $this->factorial[$i];
        //     if($i < $this->border) {
        //         $num->muti($num);
        //     } 
        //     else $num->muti($num);

        // }
        for($i=1;$i<= $exponentL; $i++) {
            $j = $this->exponent[$i];
            while($j >0) {
                $num->muti($i);
                $j--;
            }
        }
        for($i = 1; $i <= $exponentL; $i++) {
            $j = $this->exponent[$i];
            while($j < 0) {
                $num->div($i);
                $j++;
            }
        }
        return $num->print();
    }
    public function mergeSigma(Sigma $sigma) {
        if(!$this->sigma) $this->sigma = $sigma;
        else $this->sigma->nextLevel = $sigma;
    }

    public function getSegima() {return $this->sigma;}
    private function sigma($sigma, $factorial) {
        $sum = new number("0");
        $count = 0;
        while($count <= $sigma->end){

            if($count >= $sigma->start) {
                if($sigma->nextLevel) {
                    // echo "<br>{$count} :";
                    $sum->plus( $this->sigma($sigma->nextLevel, $factorial));
                }
                else {
                    $unit = new Unit($factorial, $this->border);
                    // echo $unit->calculate()."        ,";
                    $sum->plus($unit->calculate());
                }
            }
            $i = 0;
            while($i < $sigma->amountsL) {
                $amount = $sigma->amounts[$i];
                $factorial[$i] += $amount;
                if($factorial[$i] < 0 ) return $sum->print();
                $i++;
            }

            $count += 1;
        }
        // echo "sigma finish<br>";
        return $sum->print();
        
    }
}