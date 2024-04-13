<?php
namespace Src\Element;
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