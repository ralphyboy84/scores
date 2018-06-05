<?php

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


$time_start = microtime_float();

require_once("classes/Database.class.php");
require_once("classes/Calculator.class.php");

$calculator = new Calculator();
$calculator->setDbObject(new Database());
$calculator->generateScores();
$calculator->updateHistory();

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Did nothing in $time seconds\n";

?>