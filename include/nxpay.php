<?php

/**
 * @author Silence / REPT
 * @copyright 2015
 */

$merchantWalletNumber = "000000010389";
$merchantAuthKey = "71665907780263468286";
$apiProgramID = "317";
$mainaccountnumber = "0000000103890103";
$usdaccountnumber ="0000000103890121";
$endpoint = "www.nxdemo.eu/CoreServicesAPI";
//IssueLiteCard(10.00);
/*
$transactions  = GetTransactions("0000000103960233");
if (!is_int($transactions)){
    $transactionJson = json_decode(GetTransactionsList($transactions));
foreach ($transactionJson as $transactions){
    foreach ($transactions as $transaction)
   var_dump($transaction);
}
}
else 
echo "false";
*/
//echo GetCardBalanceAmount("0000000103960233");

function GetNXpayAccountCurrency(){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource

$curl = curl_init();
$url  = ("https://$endpoint/?api_service=19&communication_language=EN&trace_id=1A2E3F4Q5G6C7S88AGE&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&account=$mainaccountnumber");
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  
  CURLOPT_URL => $url

));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$resp = explode("&",$resp);
$resp = explode("=",$resp[1]);
$resp = $resp[1];

// Close request to clear up some resources
curl_close($curl);
return $resp;
}

function GetNXpayAccountBalance(){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource

$curl = curl_init();
$url  = ("https://$endpoint/?api_service=19&communication_language=EN&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&account=$mainaccountnumber");
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  
  CURLOPT_URL => $url

));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$resp = explode("&",$resp);
$resp = explode("=",$resp[0]);
$resp = $resp[1];

// Close request to clear up some resources
curl_close($curl);
return $resp;
}

function GetCardBalanceCurrency($account){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource

$curl = curl_init();
$url  = ("https://$endpoint/?api_service=19&communication_language=EN&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&account=$account");
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  
  CURLOPT_URL => $url

));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$resp = explode("&",$resp);
$resp = explode("=",$resp[1]);
$resp = $resp[1];

// Close request to clear up some resources
curl_close($curl);
return $resp;


}

function GetCardBalanceAmount($account){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource

$curl = curl_init();
$url  = ("https://$endpoint/?api_service=19&communication_language=EN&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&account=$account");
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  
  CURLOPT_URL => $url

));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$resp = explode("&",$resp);
$resp = explode("=",$resp[0]);
$resp = $resp[1];

// Close request to clear up some resources
curl_close($curl);
return $resp;


}

function GetTransactionsCount($transactionsResponse){
    $count = explode("=",$transactionsResponse[2]);
   return (int) urldecode($count[1]);
}

function GetTransactionsList($transactionsResponse){
    $count = explode("=",$transactionsResponse[3]);
    $xml = urldecode($count[1]);
    $xml = simplexml_load_string($xml);
    
    return json_encode($xml);
}
function GetTransactions($account){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource

$curl = curl_init();
$url  = ("https://$endpoint/?api_service=20&communication_language=en&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&account=$account");
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  
  CURLOPT_URL => $url

));
// Send the request & save response to $resp
$resp = (curl_exec($curl));
$resp = explode("&",$resp);

foreach ($resp as $key=>$value){
   
    if (strpos($value,'response_code') !== false) {
    $ans=$value;
}
}
$ans = explode("=",$ans);
$ans= $ans[1];
  
if ($ans==0) {
  
    curl_close($curl);
    return $resp;
}
else return intval($ans);
}

function IssueLiteCard($amount,$currency){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    global $usdaccountnumber;
    $curr = "EUR";
    if ($currency==2){
    $mainaccountnumber=$usdaccountnumber;
    $curr = "USD";
    }
    // Get cURL resource
$curl = curl_init();
$url  = "https://$endpoint/?api_service=22&communication_language=en&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&first_name=TestingFaouzij&last_name=VirtualIssueFeeFaouzij&dob_day=06&dob_month=1&dob_year=1978&gender=M&email=j.faouzi@yahoo.fr&home_phone_area=555&home_phone_number=5551258&mobile_number=55512124281&address1=158%20Road%20Gunner.&city=Seattle&state_or_province=WA&country=US&ip_address=41.85.63.205&time_zone=5&currency=$curr&program_id=317&govt_identification=202123518&govt_identification_type=SSN&govt_identification_country=US&source_account=$mainaccountnumber&amount=$amount";
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_URL => $url,

));
// Send the request & save response to $resp
$resp = curl_exec($curl);

$resp = explode("&",$resp);

foreach ($resp as $key=>$value){
   
    if (strpos($value,'response_code') !== false) {
    $ans=$value;
}
}
$ans = explode("=",$ans);
$ans= $ans[1];
  
if ($ans==0) {
  
    curl_close($curl);
    return $resp;
}
else return intval($ans);

// Close request to clear up some resources
}
function Service14($amount){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource
$curl = curl_init();
$url  = "https://www.nxdemo.eu/api?api_service=14&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&communication_language=EN&trace_id=1A2E3F4Q5G6C7S88AGE&card_number=1111000000001888&first_name=John&last_name=Smith&dob_day=21&dob_month=9&dob_year=1991&gender=M&email_address=jsmith@smitco.co&home_phone_area=555&home_phone_number=5551212&street_address_line_1=123%20Anywhere&city=Rochester&primary_administrative_subdivision_code=NY&postcode=11025&country_code=US&remote_host=19.45.208.39&timezone_type=olson&timezone_olson=America/Vancouver&govIdCode=PSP&govIdNumber=852963741&countryIssued=US&program_id=317";
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_URL => $url,

));
// Send the request & save response to $resp
$resp = curl_exec($curl);


// Close request to clear up some resources
curl_close($curl);
return $resp;
}

function IssueCard($amount){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource
$curl = curl_init();
$url  = "https://$endpoint/?api_service=14&communication_language=en&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&first_name=TestingFaouzi&last_name=VirtualIssueFeeFaouzi&dob_day=06&dob_month=1&dob_year=1978&gender=M&email=f.jouti@gmail.com&home_phone_area=555&home_phone_number=5551258&mobile_number=55512124281&address1=158%20Road%20Gunner.&city=Seattle&state_or_province=WA&country=US&ip_address=41.85.63.205&time_zone=5&currency=EUR&program_id=317&govt_identification=202123518&govt_identification_type=SSN&govt_identification_country=US&source_account=$mainaccountnumber&amount=10.00";
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_URL => $url,

));
// Send the request & save response to $resp
$resp = curl_exec($curl);


// Close request to clear up some resources
curl_close($curl);
return $resp;
}

function IssueLiteCard2($amount){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource
$curl = curl_init();
$url  = "https://$endpoint/?api_service=14&communication_language=en&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&program_id=317&source_account=$mainaccountnumber&amount=10.00";
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_URL => $url,

));
// Send the request & save response to $resp
$resp = curl_exec($curl);


// Close request to clear up some resources
curl_close($curl);
return $resp;
}


function IssueLiteCard14($amount){
    global $merchantAuthKey;
    global $merchantWalletNumber;
    global $endpoint;
    global $mainaccountnumber;
    // Get cURL resource
$curl = curl_init();
$url  = "https://$endpoint/?api_service=14&communication_language=en&merchant_auth_key=$merchantAuthKey&merchant_wallet_number=$merchantWalletNumber&first_name=TestingFaouzi&last_name=VirtualIssueFeeFaouzi&dob_day=06&dob_month=1&dob_year=1978&gender=M&email=f.jouti@gmail.com&home_phone_area=555&home_phone_number=5551258&mobile_number=55512124281&address1=158%20Road%20Gunner.&city=Seattle&state_or_province=WA&country=US&ip_address=41.85.63.205&time_zone=5&currency=EUR&program_id=317&govt_identification=202123518&govt_identification_type=SSN&govt_identification_country=US&source_account=$mainaccountnumber&amount=10.00";
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_URL => $url,

));
// Send the request & save response to $resp
$resp = curl_exec($curl);

echo $resp;
// Close request to clear up some resources
curl_close($curl);
}

function ConvertToXml($string){
    $tofind = array("%3C","%3F","%20","%3D","%22","%3E","%2F");
    $replace = array("<","?"," ","=",'"',">",'/');
  return  str_replace($tofind,$replace,$string);
}
?>