<?php

/**
 * @author Silence / REPT
 * @copyright 2015
 */
require_once("functions.php");
$phone = $_POST["phone"];
$flight = $_POST["flight"]; 
echo InsertNewNotification($phone,$flight);

?>