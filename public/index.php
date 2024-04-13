<?php
require "../bootstrap.php";

use Src\Controller\CalculatorController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:
$route =  explode('/', $_SERVER['REQUEST_URI'])[1];

if($route === "calculator") {
    $controller = new CalculatorController($requestMethod);
    $controller->accumulateUnit();
}