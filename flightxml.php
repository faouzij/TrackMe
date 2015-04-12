<?php

/**
 * @author Silence / REPT
 * @copyright 2015
 */
error_reporting(E_ERROR | E_PARSE);

require_once('SOAP/Client.php');

$DirectFlight_Authentication = array(
       'user'          => 'faouzij',
       'pass'          => '711b717bc22b11d583865e1dd518c6d761439ddf',
    );

$wsdl_url = 'http://flightaware.com/commercial/flightxml/data/wsdl1.xml';
$WSDL = @new SOAP_WSDL($wsdl_url,$DirectFlight_Authentication);
$soap = @$WSDL->getProxy();

function GetAirportCodeFromName($name){
    $result = file_get_contents("http://airportcode.riobard.com/search?q=$name&fmt=JSON");
   // echo "http://airportcode.riobard.com/search?q=$name&fmt=JSON";
$result = json_decode($result);
$name = urldecode($name);
foreach ($result as $airports){
    if ($airports->name == $name)
    return ($airports->code);
    }
  

}

function GetAirportCountryfromIATA($iata){
      $result = file_get_contents("http://airport-codes.herokuapp.com/airports/$iata");
      $result = json_decode($result);
      return $result->country;
}

function GetAirportICAOfromIATA($iata){
      $result = file_get_contents("http://airport-codes.herokuapp.com/airports/$iata");
      $result = json_decode($result);
      return $result->icao;
}

function GetCountryCodeFromCountryName($name){
    $result = file_get_contents("https://restcountries.eu/rest/v1/name/$name");
   $result = json_decode($result);
  // var_dump($result);
    return strtolower($result[0]->alpha2Code);
}

function GetAirportLatitudeLongitudeFromAddress($address){
    $address = urlencode($address);
    $result = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$address");
    //echo "https://maps.googleapis.com/maps/api/geocode/json?address=$address";
   $result = json_decode($result);
  // var_dump($result);
  //var_dump($result->results[0]->geometry->bounds->northeast);
    return $result->results[0]->geometry->bounds->northeast->lat.':'.$result->results[0]->geometry->bounds->northeast->lng;
 //   return strtolower($result[0]->alpha2Code);
}
//echo GetAirportLatitudeLongitudeFromAddress("Mohamed 5 INTL");


//echo getIATAfromICAO("GMMN");
function getIATAfromICAO($icao){
    $file = fopen("airports.txt",'r');
 while (($line = fgets($file)) !== false) {
      if (strpos($line,"$icao") !== false) {
    $data = explode(",",$line);
    return str_replace('"','',$data[4]);
    break;
}
    }

    fclose($handle);
}

function getAirportNamefromICAO($icao){
    $file = fopen("airports.txt",'r');
 while (($line = fgets($file)) !== false) {
      if (strpos($line,"$icao") !== false) {
    $data = explode(",",$line);
    return str_replace('"','',$data[1]);
    break;
}
    }

    fclose($handle);
}

function getCityfromICAO($icao){
    $file = fopen("airports.txt",'r');
 while (($line = fgets($file)) !== false) {
      if (strpos($line,"$icao") !== false) {
    $data = explode(",",$line);
    return str_replace('"','',$data[1]);
    break;
}
    }

    fclose($handle);
}
//var_dump(GetFlightsFromOriginDestination(GetAirportICAOfromIATA("IAH"),GetAirportICAOfromIATA("BOS")));
function distance($lat1, $lon1, $lat2, $lon2, $unit="K") {
 
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);

  $miles = $dist * 60 * 1.1515;

  $unit = strtoupper($unit);
  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}



function getCountryfromICAO($icao){
    $file = fopen("airports.txt",'r');
 while (($line = fgets($file)) !== false) {
      if (strpos($line,"$icao") !== false) {
    $data = explode(",",$line);
    return str_replace('"','',$data[2]);
    break;
}
    }

    fclose($handle);
}
function Arrived($destination,$ident){
    global $soap;
 $result=$soap->Arrived($destination);
 $flights = $result->arrivals;
 foreach($flights as $flight) {
    if ($flight->ident = $ident) return true;
 }
 return false;  
}

//var_dump($result);
function getArrivedFromFlightNumber($name){
    global $soap;
$flight = $soap->InFlightInfo("$name");
       $altitudestatus = $flight->altitudeStatus;
            $speed = $flight->groudspeed;
            $origin = $flight->origin;
            $flightid = $flight->faFlightID;
            $destination = $flight->destination;
            $departure = $flight->departureTime;
            $ident = $flight->ident;
            $arrival = $flight->arrivalTime;
            $altitude  = $flight->altitude;
            $heading  = $flight->heading;
            $longitude = $flight->longitude;
            $latitude = $flight->latitude;
            //$arrived = Arrived($destination,$ident);
            $city = getCityfromICAO($origin);
            $country = getCountryfromICAO($origin);
            $destinationData = explode(":",GetAirportLatitudeLongitudeFromAddress(getAirportNamefromICAO($destination)));
            $destinationlat = $destinationData[0];
            $destinationlng = $destinationData[1];
            //echo "Lat $destinationlat lng $destinationlng";
            $originData = explode(":",GetAirportLatitudeLongitudeFromAddress($origin));
            $originlat = $originData[0];
            $originlng = $originData[1];
            
            $distanceLeft = round(distance($latitude,$longitude,$destinationlat,$destinationlng),0);
            $countrycode = GetCountryCodeFromCountryName(GetAirportCountryfromIATA(getIATAfromICAO($destination)));
              $distanceTraversed = round(distance($latitude,$longitude,$originlat,$originlng),0);
          
            $totalDistance = round(distance($originlat,$originlng,$destinationlat,$destinationlng),0);
            $percentage = round(($distanceTraversed)/($totalDistance)*100,0);
if($percentage>=100) return true; else return false;

}



//echo GetCountryCodeFromCountryName(GetAirportCountryfromIATA("CMN"));

/*
$result = @$soap->Enroute('KIAH',20,'airline',0);


$flights = @$result->enroute;
    $count = count($flights);
  echo "Number Of flights is $count<br/>";
  
foreach ($flights as $flight) {

  
  print @$flight->ident . " (" . @$flight->aircrafttype . ") \t" .
    @$flight->originName . " (" . @$flight->origin . ")\n";
    echo "<br/>";
}
*/
function GetFlightMap($name){
    global $soap;
    $result = $soap->MapFlight_Beta($name,400,400);
    return $result;
}
function GetFlightsFromOriginDestination($origin,$destination){
    //$result = $soap->InFlightInfo("RAM764");
global $soap;
//echo "-origin $origin -destination $destination";
$result = $soap->Search("-origin $origin -destination $destination",20,0);
//var_dump($result);
$flights = $result->aircraft;
return $flights;
}

function GetFlightsFromID($id){
    global $soap;
    $result = $soap->InFlightInfo("$id");


return $result;
}
//Zvar_dump(GetFlightsFromOriginDestination(GetAirportICAOfromIATA("GVA"),GetAirportICAOfromIATA("RAK")));
/*
$result = $soap->InFlightInfo("RAM764");
var_dump($result);
/*
$result = $soap->Search("-origin GMMN -destination LFPG",20,0);
$flights = $result->aircraft;
foreach($flights as $flight){
    //var_dump($flight);
    //die();
    echo "FlightID is ".$flight->faFlightID;
    
}*/
//$result->AllAirports;
//var_dump($result)
/*
$city = "casablanca";
$result = file_get_contents("http://airportcode.riobard.com/search?q=$city&fmt=JSON");
$result = json_decode($result);
foreach ($result as $airports){
    echo('
 Airport Code :'.$airports->code.'
 <br/>Airport Name : '.$airports->name.'
 <br/>--------------<br/>');
 
 }
 */
 

?>