<?php

/**
 * @author Silence / REPT
 * @copyright 2015
 */

$city = $_GET["city"];
$result = file_get_contents("http://airportcode.riobard.com/search?q=$city&fmt=JSON");
$result = json_decode($result);
$array = array();
foreach ($result as $airports){
    array_push($array,$airports->name);
    }
    $result = json_encode($array);
    echo $result;
?>