<?php

    class number {
        private $s = array();
        private $front = 0;
        private $base =1000000000;
        private $limit ;
        public function __construct($num, $base =0) {
            $this->s[0] = 0;
            // $this->muti(1);
            if(gettype($num) == 'string') $this->set($num);
            else return false;
            if($base) $this->base = $base;
            $this->limit =  pow(10, floor(log(PHP_INT_MAX, 10) ) )/ $this->base;

            return true;
        }
        public function muti($m) {
            $i = $this->front;
            if($this->limit <= $m) return ;
            $this->s[$this->front+1] =0 ;
            if($this->s[$this->front]*$m > $this->base ){
                $this->front ++;
            } 
            while($i >= 0 ) {
                $this->s[$i]  = $this->s[$i] * $m;
                $this->s[$i+1] += floor($this->s[$i] / $this->base );
                $this->s[$i] = $this->s[$i] % $this->base;
                $i--;
            }


        }
        public function div($d) {
            $i = $this->front;
            if($i <0) return;
            if($this->limit <= $d) return ;
            if($this->s[$this->front]/$d <1 ){
                $this->front --;
            } 

            while($i >= 0 ) {
                $temp = $this->s[$i]  ;
                $this->s[$i] = (int)($this->s[$i]/ $d) ;
                if($i  == 0) break;
                $this->s[$i-1] += ($temp % $d) * $this->base;
                $i--;
            }


        }
        public function print() {
            $str = "";
            $i = $this->front;
            while($i >= 0 ) {
                $str .= strval($this->s[$i]);
                $i--;
            }
            return $str;
        }
        public function len() {
            return $this->front;
        }
        public function set($num) {
            $k = log($this->base, 10);
            $str_len = strlen($num) ;
            $i = 0;
            while($str = substr($num, $str_len-$k)) {
                $this->s[$i] = intval($str);
                $num = substr($num, 0, $str_len-$k);
                $i++;
                if(strlen($num) < $k && intval($num) > 0 ) {
                    $this->s[$i] = intval($num);
                    $this->front = $i;
                    break;
                }
            }
            return $num;
        }
        public function plus($num) {
            $k = log($this->base, 10);
            $str_len = strlen($num) ;
            $i = 0;
            while($str = substr($num, $str_len-$k)) {
                $this->s[$i] += intval($str);
                $num = substr($num, 0, $str_len-$k);
                $i++;
                if(strlen($num) < $k && intval($num) > 0 ) {
                    $this->s[$i] += intval($num);
                    $this->front = $i;
                    break;
                }
            }
            return $num;
        }
    }
    
    class Sigma {
        public Array $amounts ;
        public $amountsL = 0;
        public ?Sigma $nextLevel = null;
        public $end;
        public $start;

        public function __construct(Array $amounts, $end, $start) {
            $this->amounts = $amounts;
            $this->amountsL = count($amounts);
            $this->end = $end;
            $this->start = $start;
        }
    }
    class Unit {
        protected $exponent= array();
        protected $factorial = array();
        protected $border = 0;
        // protected $bottom= array();
        protected ?Sigma $sigma = null;
        public function __construct($factorial, $border) {
            $factorialL= count($factorial);
            $sectionA = array_slice($factorial,0, $border);
            $sectionB = array_slice($factorial, $border);
            sort($sectionA);
            sort($sectionB);
            $factorial =  array_merge($sectionA, $sectionB);
            $this->factorial = $factorial;
            $this->border = $border;

            for($i=0; $i <= $factorial[0]; $i++) $this->exponent[$i] = 0;
            $i = 0;
            while($i < $factorialL ) {
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
            $this->mergeSegima($u->getSegima());
            $max = max(count($anotherE ), count($this->exponent));
            for($i = 1; $i < $max; $i++) {
                if($this->exponent[$i] >0) {
                    $this->exponent[$i] += $anotherE[$i];
                }
                else $this->exponent[$i] = $anotherE[$i];
            }
            return $this;
        }

        public function getExponent() {
            return $this->exponent;
        }
        public function caculate() {
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
            for($i=1;$i< $exponentL; $i++) {
                $j = $this->exponent[$i];
                while($j >0) {
                    $num->muti($i);
                    $j--;
                }
            }
            for($i = 1; $i < $exponentL; $i++) {
                $j = $this->exponent[$i];
                while($j < 0) {
                    $num->div($i);
                    $j++;
                }
            }
            return $num->print();
        }
        public function setSegima(Sigma $sigma) {
            $this->sigma = $sigma;
            // $this->sigma =  new Sigma();
            // $sigma = $this->sigma;
            // foreach ( $amounts as $levelAmounts) {
            //     $sigma = new Sigma();
            //     foreach ($levelAmounts as $pos => $amount) {
            //         $sigma->set($pos, $amount);
            //     }
            //     $sigma = $sigma->nextLevel;
            // }
            
        }
        public function mergeSegima(Sigma $sigma) {
            $this->sigma->nextLevel = $sigma;
        }

        public function getSegima() {return $this->sigma;}
        private function sigma($sigma, $factorial) {
            $sum = new number("0");
            $count = 0;
            while($count <= $sigma->end){

                if($count >= $sigma->start) {
                    if($sigma->nextLevel) {
                        echo "<br>{$count} :";
                        $sum->plus( $this->sigma($sigma->nextLevel, $factorial));
                    }
                    else {
                        $unit = new Unit($factorial, $this->border);
                        echo $unit->caculate()."        ,";
                        $sum->plus($unit->caculate());
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
            echo "sigma finish<br>";
            return $sum->print();
            
        }
    }

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

        }
        public function string() {
            $a =  $this->factorial[0];
            $b = $this->factorial[1];
            return "P({$a},{$b})";
        }
    }
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
        }
        public function string() {
            $a = $this->factorial[0];
            $b = $this->factorial[2];
            return "C({$a},{$b})";
        }
    }
    class H  {
        public function __construct(int $a, int $b) {
            if ($a < $b) return false; 
            return new C($a+$b -1, $a);
        }
    }

    $a = new C(10, 0);
    // echo $a->caculate()."<br>";
    $sigma = new Sigma(array(0,-1,1), 10, 0);
    $sigma->nextLevel = new Sigma(array(0,-2, 2),5 , 0);
    $a->setSegima($sigma);  
    echo $a->caculate()."<br>";
