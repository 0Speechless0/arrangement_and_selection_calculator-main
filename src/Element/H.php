<?php
namespace Src\Element;
    class H  {
        public function __construct(int $a, int $b) {
            if ($a < $b) return false; 
            return new C($a+$b -1, $a);
        }
    }