<?php
namespace Src\Controller;
use Src\Element\P;
use Src\Element\C;
use Src\Element\H;
use Src\Element\Number;
use Src\Element\Sigma;
class CalculatorController {

    private $requestMethod;
    public function __construct($method) {
        $this->requestMethod = $method;
    }
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(array ( 'a' => 1 , 'b' => 2 , 'c' => 3 , 'd' => 4 , 'e' => 5 ));
                break;
            case 'POST':
                $input = json_decode(file_get_contents('php://input'), TRUE);
                var_dump($input);
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = null;
                break;
            case 'PUT':
                break;
            case 'DELETE':
                break;
            default:
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function accumulateUnit() {
        $input = json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->checkRequest($input, ["number", "units"])) return ;
        $num = new Number($input["number"]);
        foreach($input["units"] as $key => $value) {
            if(!$this->checkRequest($value, ["type", "n1", "n2"])) return ;
            switch($value["type"]) {
                case 'P' :
                    $units[$key] = new P($value['n1'], $value['n2']);
                    break;
                case 'C' : 
                    $units[$key] = new C($value['n1'], $value['n2']);
                    break;
                case 'H' :
                    $units[$key] = new H($value['n1'], $value['n2']);
                    break;
                case 'N' :
                    $units[$key] = new N($value['n1'], $value['n2']);
                    break;

                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request ';
                return ;
            }
            foreach ($value["sigma"] as $sigma) {
                if(!$this->checkRequest($sigma, ["amounts", "end", "start" ])) return ;
                $units[$key]->mergeSigma(new Sigma($sigma["amounts"], $sigma["end"], $sigma["start"]));
            }
        }
        $iteration = $units[0];
        $unitL = count($units);
        for($i = 1; $i < $unitL; $i++) {
            $iteration->muti($units[$i]);
        }
        $num->plus($iteration->calculate());
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([ "answer" => $num->print()]);
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function setSigma(Unit $unit, $sigmas) {
        foreach ($sigmas as $sigma) $unit->mergeSigma($sigma);
    }
    private function checkRequest($input, $arr) {
        if($this->requestMethod != 'POST') {
            $response['status_code_header'] = 'HTTP/1.1 405 ';
            header($response['status_code_header']);
            return false;
        }
        foreach ($arr as $value) {
            if($input[$value] == null) {
                $response['status_code_header'] = 'HTTP/1.1 400 Bad Request: no '.$value;
                header($response['status_code_header']);
                return false;
            }
        }
        return true;
    }
}