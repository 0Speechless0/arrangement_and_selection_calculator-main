<?php
namespace Src\Element;

class Number {
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