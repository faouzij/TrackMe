<?php

/**
 * @author Silence / REPT
 * @copyright 2015
 */

require_once("flightxml.php");
$flight = $_POST["flight"]; 
echo "data:image/png;base64,".GetFlightMap($flight);

?>