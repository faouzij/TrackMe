<?php


@session_start();
 ob_start();
 $_SESSION["register"]=0; 
require_once("config.php");
require_once("nxpay.php");

date_default_timezone_set('Asia/Tel_Aviv');

function processSingleAccount($email,$name,$ua){
 global $connection;
 $time = time();
 $ip = $_SERVER['REMOTE_ADDR'];
 $select = $connection->query("UPDATE slots SET SL_STATUS= '1',SL_SUBMIT_DATE='$time',SL_SUBMIT_IP='$ip',SL_SUBMIT_UA='$ua',SL_SUBMIT_VPS='Online' WHERE SL_EMAIL='$email' AND SL_NAME='$name'");
 $select = $connection->query("INSERT INTO archive (SELECT * from slots WHERE SL_EMAIL='$email' AND SL_NAME='$name')");
 $select = $connection->query("DELETE from slots WHERE SL_EMAIL='$email' AND SL_NAME='$name'");
}

function getGRSubscribersNumber($apin){

    $api = new GetResponse($apin);
    $num = $api->get_contacts_amount_per_account();
$num = get_object_vars($num);
$num = array_values($num);  
return $num[0];
}

function UnsubscribeGRuser($email,$uid){
    $apin = getAffiliateAttributeByUID("GR_API",$uid);
    $api = new GetResponse($apin);    
    $id = $api->getContactsByEmail($email);
    $id = get_object_vars($id);
    $id = array_keys($id);
    $id = $id[0];
    return ($api->deleteContact($id));
}

function getSingleSlot($slot){
      global $connection;

 $select = $connection->query("SELECT SL_ID number from slots WHERE SL_NUMBER = '$slot' AND SL_STATUS='0' ORDER BY SL_CREATE_DATE DESC LIMIT 1");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $email = getSlotAttribute("SL_EMAIL",$id);
 $name = getSlotAttribute("SL_NAME",$id);
return "$email:$name";
 }
 }

function sendContactMessage($name,$email,$number,$phone,$date,$msg){
    $mail = new PHPMailer();
  // ---------- adjust these lines ---------------------------------------
    $mail->Username = "lesecretdelartisan@gmail.com"; // your GMail user name
    $mail->Password = "joutifaouzi123"; 
    $mail->AddAddress("aigithfarook2@gmail.com"); // recipients email
    $mail->FromName = "Pan Am's Joint"; // readable name

    $mail->Subject = "New Reservation/Contact Message";
    $mail->Body    = "Reservation/Contact Message Details :\n
    Name : ".$name."\n
    Email : ".$email."\n
    Phone Number : ".$phone."\n
    Seats Reserved : ".$number."\n
    Date :  ".$date."\n
    Message :  ".$msg."\n"; 
    //-----------------------------------------------------------------------

    $mail->Host = "ssl://smtp.gmail.com"; // GMail
    $mail->Port = 465;
    $mail->IsSMTP(); // use SMTP
    $mail->isHtml = true;
    $mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->From = $mail->Username;
    if(!$mail->Send())
        echo 0;
    else
        echo 1;
    
}

function make_safe($variable) 
{
   return strip_tags((trim($variable)));
   
}


function getListCount($fid){
      global $connection;

 $select = $connection->query(" SELECT USR_FLIST_COUNT FROM USERS WHERE USR_FID = '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   return $enregistrement->USR_FLIST_COUNT;
}

function getFIDfromToken($token){
      global $connection;

 $select = $connection->query(" SELECT USR_FID FROM USERS WHERE USR_TOKEN = '$token'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   return $enregistrement->USR_FID;
}

function CheckFID($fid){
      global $connection;
      
     

 $select = $connection->query("SELECT COUNT(USR_ID) number FROM users WHERE USR_INSTA_ID = '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if (isset($enregistrement->number)) return ($enregistrement->number>0);
   else return false;
  
}

function GetPhoneCode($phone){
   
      global $connection;

 $select = $connection->query("SELECT V_CODE number FROM verification WHERE V_NUMBER = '$phone'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  return ($enregistrement->number);
 
}

function getUserID($username){
      global $connection;

 $select = $connection->query("SELECT USR_ID FROM users WHERE USR_USERNAME = '$username'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  return ($enregistrement->USR_ID);
 
}


function getUsername($fid){
      global $connection;

 $select = $connection->query(" SELECT USR_USERNAME FROM USERS WHERE USR_ID = '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   return $enregistrement->USR_USERNAME;
}

function getTotalViewsPurchased($fid){
      global $connection;

 $select = $connection->query("SELECT SUM(OD_AMOUNT)number from orders where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getTotalViewsDelivered($fid){
      global $connection;

 $select = $connection->query("SELECT SUM(OD_AMOUNT)number from orders where USR_ID= '$fid' AND OD_STATUS='2'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
    if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getTotalVideosServed($fid){
      global $connection;

 $select = $connection->query("SELECT COUNT(VID_ID)number from videos where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getUserCredits($fid){
      global $connection;

 $select = $connection->query("SELECT USR_CREDITS number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getTotalOrders($fid){
      global $connection;

 $select = $connection->query("SELECT COUNT(OD_ID) number from orders  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getTotalTransactions($fid){
      global $connection;

 $select = $connection->query("SELECT COUNT(TRA_ID) number from transactions  where TRA_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function CalculateAmountToPay($fid,$amount,$type,$retention){
      global $connection;
$amount = doubleval($amount)/1000;
 $select = $connection->query("SELECT USR_RATENM,USR_RATEUS,USR_RATEHRNM,USR_RATEHRUS from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  $nm = $enregistrement->USR_RATENM ;
   $us = $enregistrement->USR_RATEUS ;
    $hrnm = $enregistrement->USR_RATEHRNM ;
    $hrus = $enregistrement->USR_RATEHRUS ;
    if ($retention){
  if ($type=="nm")
  return $amount*($nm+$hrnm);
  else if ($type=="us")
  return $amount*($us+$hrus);
  else
   return "Error";
  }
  
  else{
     if ($type=="nm")
  return $amount*($nm);
  else if ($type=="us")
  return $amount*($us);
  else
  return "Error";
  }
  
}


function CalculateAmountToPayJS($fid,$amount,$type,$retention){
       global $connection;
$amount = doubleval($amount)/1000;
 $select = $connection->query("SELECT USR_RATENM,USR_RATEUS,USR_RATEHRNM,USR_RATEHRUS from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  $nm = $enregistrement->USR_RATENM ;
   $us = $enregistrement->USR_RATEUS ;
    $hrnm = $enregistrement->USR_RATEHRNM ;
    $hrus = $enregistrement->USR_RATEHRUS ;
    if ($retention){
  if ($type=="nm")
  echo $amount*($nm+$hrnm);
  else if ($type=="us")
  echo $amount*($us+$hrus);
  else
   echo "Error";
  }
  
  else{
     if ($type=="nm")
  echo $amount*($nm);
  else if ($type=="us")
  echo $amount*($us);
  else
  echo "Error";
  }
  }
  

function getLastLoginDate($fid){
      global $connection;

 $select = $connection->query("SELECT USR_LASTLOGIN number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return date('m/d/Y - H:i:s', $enregistrement->number);
    else return 0;
}

function geTableOrders($fid){
      global $connection;

 $select = $connection->query("SELECT OD_ID number from orders  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
  
  $stat = getOrderStatus($id);
  $hr = getOrderHR($id);
  echo "
    <tr>
      <td>".getOrderDate($id)."</td>
       <td>".getOrderVideoLink($id)."</td>
      <td>".getOrderVideoStartViews($id)."</td>
      <td>".getOrderVideoCurrentViews($id)."</td>
      <td>".getOrderAmount($id)."</td>
      <td>".getOrderCountry($id)."</td>";
      
      if($hr==0) echo "<td><span class='label label-red'>Disabled</span></td>"; else echo "<td><span class='label label-green'>Enabled</span></td>";
      if($stat==0) echo "<td><span class='label label-orange'>Pending</span></td>"; else echo "<td><span class='label label-green'>Completed</span></td>";
      
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
        <td>You Have No Purchases Yet</td>";
     }
   
 
  
}
}


function geTableOrdersALL(){
      global $connection;

 $select = $connection->query("SELECT OD_ID number from orders ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
  
  $stat = getOrderStatus($id);
  $hr = getOrderHR($id);
  echo "
    <tr>
      <td>".getOrderDate($id)."</td>
       <td>".getOrderVideoLink($id)."</td>
      <td>".getOrderVideoStartViews($id)."</td>
      <td>".getOrderVideoCurrentViews($id)."</td>
      <td>".getOrderAmount($id)."</td>
      <td>".getOrderCountry($id)."</td>";
      
      if($hr==0) echo "<td><span class='label label-red'>Disabled</span></td>"; else echo "<td><span class='label label-green'>Enabled</span></td>";
      if($stat==0) echo "<td><span class='label label-orange'>Pending</span></td>"; else echo "<td><span class='label label-green'>Completed</span></td>";
      echo "<td><a href='status.php?id=$id'>Change Status</a></td>";
      echo "<td>".getOrderUsername($id)."</td>";
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
        <td>You Have No Purchases Yet</td>
        <td>You Have No Purchases Yet</td>";
     }
   
 
  
}
}



function geTableTransactions($fid){
      global $connection;

 $select = $connection->query("SELECT TRA_ID number from transactions  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
  
  $stat = getOrderStatus($id);
  $hr = getOrderHR($id);
  echo "
    <tr>
      <td>".getTransactionDate($id)."</td>
       <td>".getTransactionAmount($id)." ".getTransactionCurrency($id)."</td>
      <td>".getTransactionEmail($id)."</td>
      <td>".getTransactionPayerID($id)."</td>";
      
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}


function geTableAffiliates(){
      global $connection;
echo '<table style="width:100%">
<tr><th>Webform ID</th>
<th>Webform UID</th>
    <th>API</th> 
     
    <th>Join Date</th></tr>
  

';
 $select = $connection->query("SELECT AFF_ID number from affiliates ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
  $grid = getAffiliateAttribute("GR_ID",$id); 
  $api = getAffiliateAttribute("GR_API",$id);
 $uid= getAffiliateAttribute("GR_UID",$id);  
  $date = getAffiliateAttribute("AFF_DATE",$id);
  echo "
    <tr>
      <td>".$grid."</td>
<td>".$uid."</td>
       <td>".$api."</td>
      <td>".FormatDate($date)."</td>
      </tr>";
      
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
echo "</table>";
}


function getSlotsTable(){
      global $connection;

 $select = $connection->query("SELECT SL_ID number from slots  where SL_STATUS = '0' ORDER BY SL_CREATE_DATE DESC LIMIT 12000 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
  
  
  echo "
    <tr>
      <td>".getSlotAttribute("SL_EMAIL",$id)."</td>
       <td>".getSlotAttribute("SL_NAME",$id)."</td>
          <td>".getSlotAttribute("SL_NUMBER",$id)."</td>
                       <td>".FormatDate(getSlotAttribute("SL_CREATE_DATE",$id))."</td>
         ";
     }

 
  
}
}



function getArchiveTable(){
      global $connection;

 $select = $connection->query("SELECT SL_ID number from archive ORDER BY SL_SUBMIT_DATE DESC");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs

  $id = $enregistrement->number;
  
  $submitdate = getArchiveAttribute("SL_SUBMIT_DATE",$id);
  $status = getArchiveAttribute("SL_STATUS",$id);
  $submitua = getArchiveAttribute("SL_SUBMIT_UA",$id);
   $submitip = getArchiveAttribute("SL_SUBMIT_IP",$id);
     $vps = getArchiveAttribute("SL_SUBMIT_VPS",$id);
   if ($status=='0') $status="Not Processed"; else $status="Imported";
   if ($submitua=='0') $submitua="N/A";
   if ($submitip=='0') $submitip="N/A";
      if ($vps=='0') $vps="N/A";
   if ($submitdate==0) $submitdate="N/A"; else $submitdate=FormatDate($submitdate);
    
  echo "
  
    <tr>
      <td>".getArchiveAttribute("SL_EMAIL",$id)."</td>
       <td>".getArchiveAttribute("SL_NAME",$id)."</td>
          <td>".getArchiveAttribute("SL_NUMBER",$id)."</td>
             <td>".FormatDate(getArchiveAttribute("SL_CREATE_DATE",$id))."</td>
             <td>".$status."</td>
             <td>".$submitdate."</td>
             <td>".$submitip."</td>
             <td>".$submitua."</td>
              <td>".$vps."</td>
             </tr>";
     

 
  
}
}


function geTableTransactionsAll(){
      global $connection;

 $select = $connection->query("SELECT TRA_ID number from transactions  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
  
  $stat = getOrderStatus($id);
  $hr = getOrderHR($id);
  echo "
    <tr>
      <td>".getTransactionDate($id)."</td>
       <td>".getTransactionAmount($id)." ".getTransactionCurrency($id)."</td>
      <td>".getTransactionEmail($id)."</td>
      <td>".getTransactionPayerID($id)."</td>
       <td>".getTransactionUsername($id)."</td>";
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}


function getBanksCards(){
      global $connection;

 $select = $connection->query("SELECT B_ID number from banks ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
   $name = getBANKattribute("B_NAME",$id);
  $number = getBANKattribute("B_NUMBER",$id);
 echo '
  <div class="punchline_text_box">
          <div class="left">
            <h5>Bank Name : <input type="text" actionid="name'.$id.'" id="name" value="'.$name.'" /></h5>
            <p>Bank Number : <input type="text" id="number" actionid="number'.$id.'" value="'.$number.'"/></p>
          </div>
          <div class="right"> <a class="knowmore_but" style="float: left;margin:0px" href="#" action="save" actionid="'.$id.'">Save</a>  <a class="knowmore_but" style="float: left;margin:0px;background-color:#F41C1C;" href="removebank.php?id='.$id.'">Delete</a> </div>
          
        </div>
 ';
 
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}

function getBanksPanels(){
      global $connection;

 $select = $connection->query("SELECT B_ID number from banks ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
   $name = getBANKattribute("B_NAME",$id);
  $number = getBANKattribute("B_NUMBER",$id);
 echo '

	<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											</div>
						
										<h2 class="panel-title">'.$name.'</h2>
									</header> 
 
 
 
 <div class="panel-body">
                                  
									
											<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Bank Name </label>
												<div class="col-md-6">
													<input type="text" class="form-control"  actionid="name'.$id.'" id="name" value="'.$name.'" placeholder="Bank name">
												</div>
											</div>
                                            
                                            	<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Bank Number</label>
												<div class="col-md-6">
													<input type="text" class="form-control"  id="number" actionid="number'.$id.'" value="'.$number.'" placeholder="Bank Number">
												 
                                            	</div>
                                                
											</div>
                                            <div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault"> </label>
												<div class="col-md-6">
												  <button type="submit"  action="save" actionid="'.$id.'" class="btn btn-success btn-lg">Save</button> 
						 <button type="submit"  action="delete" actionid="'.$id.'" class="btn btn-danger btn-lg" onclick="location.href = \'removebank.php?id='.$id.'\';">Delete</button> 
						
                                                </div>
											</div>
                                           
											
						
										
										
									</div>
                                    	</section>
 
 ';
 
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}

function getMyBankCard(){
      global $connection;

 $select = $connection->query("SELECT MB_ID number,B_ID number2 from mybank ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
    $bid = $enregistrement->number2;
   $account = getMYBANKattribue("MB_ACCOUNT_NUMBER",$id);
  $branch = getMYBANKattribue("MB_BRANCH_NUMBER",$id);
 echo '
  <div class="punchline_text_box">
          <div class="left">
          
            <h5>Bank : 
            <label class="select">
                <select name="bankid"  id="bankid" actionid="'.$bid.'">
                  <option value="0" selected disabled>Choose Bank</option>
                 ';
                 getBanksSelect();
                 
                 echo'
                 
                </select>
                <i></i> </label></h5>
          
             <h5>Account# : <input type="text" actionid="account'.$id.'" id="accountnumber" value="'.$account.'" /></h5>
               <h5>Branch#: <input type="text" actionid="branch'.$id.'" id="branchnumber" value="'.$branch.'" /></h5>
          </div>
          <div class="right"> <a class="knowmore_but" style="float: left;margin:0px" href="#" action="save" actionid="'.$id.'">Save</a>  </div>
          
        </div>
 ';
 
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}

function getMyBankPanel(){
      global $connection;

 $select = $connection->query("SELECT MB_ID number,B_ID number2 from mybank LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;
    $bid = $enregistrement->number2;
   $account = getMYBANKattribue("MB_ACCOUNT_NUMBER",$id);
  $branch = getMYBANKattribue("MB_BRANCH_NUMBER",$id);
 echo '
 
        
        
        
        <section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											</div>
						
										<h2 class="panel-title">Edit My Bank Account</h2>
									</header> 
 
 
 
 <div class="panel-body">
                                  
									
                                    	<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Choose Bank </label>
												<div class="col-md-6">
												
                                                
                                                <select name="bankid"  id="bankid" class="form-control" actionid="'.$bid.'">
                  <option value="0" selected disabled>Choose Bank</option>
                 ';
                 getBanksSelect();
                 
                 echo'
                 
                </select>
                                                
                                                	</div>
											</div>
                                            
                                    
											<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Account #</label>
												<div class="col-md-6">
													<input type="text" class="form-control" actionid="account'.$id.'" id="accountnumber"  placeholder="Account Number">
												</div>
											</div>
                                            
                                            	<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Branch #</label>
												<div class="col-md-6">
													<input type="text" class="form-control"  actionid="branch'.$id.'" id="branchnumber" placeholder="Branch Number">
												 
                                            	</div>
                                                
											</div>
                                            <div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault"> </label>
												<div class="col-md-6">
												  <button type="submit"  action="save" actionid="'.$id.'" class="btn btn-success btn-lg">Save</button> 
						
                                                </div>
											</div>
                                           
											
						
										
										
									</div>
                                    	</section>
        
        
        
        
 ';
 
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}


function getMyFeesCard(){
      global $connection;

 $select = $connection->query("SELECT FE_ID number from fees limit 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;

   $commission = getCommissionPercentage()*100;
  $annual = getAnnualCardFee();
 echo '
  <div class="punchline_text_box">
          <div class="left">
          
          
             <h5>Commission (%) : <input type="text"  id="commission" value="'.$commission.'" /></h5>
               <h5>Plus Card Fee (NIS): <input type="text"  id="annual" value="'.$annual.'" /></h5>
          </div>
          <div class="right"> <a class="knowmore_but" style="float: left;margin:0px" href="#" action="save" actionid="'.$id.'">Save</a>  </div>
          
        </div>
 ';
 
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}

function getMyFeesPanel(){
      global $connection;

 $select = $connection->query("SELECT FE_ID number from fees limit 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  if ($select->rowCount()>0){
  $id = $enregistrement->number;

   $commission = getCommissionPercentage()*100;
  $annual = getAnnualCardFee();
 echo '
  
        
        
        
        
         <section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
										</div>
						
										<h2 class="panel-title">Edit Fees</h2>
									</header> 
 
 
 
 <div class="panel-body">
                                  
									
                                    	<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Commission (%) </label>
												<div class="col-md-6">
												
                                             	<input type="text" class="form-control"  id="commission" value="'.$commission.'" placeholder="Commission (%)">
                                                
                                                	</div>
											</div>
                                            
                                    
											<div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault">Plus Card Annual Fees (NIS)</label>
												<div class="col-md-6">
													<input type="text" class="form-control"id="annual" value="'.$annual.'" placeholder="Plus Card Annual Fees (NIS)">
												</div>
											</div>
                                            
                                            
                                            <div class="form-group">
												<label class="col-md-3 control-label" for="inputDefault"> </label>
												<div class="col-md-6">
												  <button type="submit"  action="save" actionid="'.$id.'" class="btn btn-success btn-lg">Save</button> 
						
                                                </div>
											</div>
                                           
											
						
										
										
									</div>
                                    	</section>
        
        
 ';
 
     }
     else{
        echo "
    <tr>
      <td>You Have No Purchases Yet</td>
       <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
      <td>You Have No Purchases Yet</td>
    ";
     }
   
 
  
}
}

function RemoveItemFromCart($item){
    if (!isset($_SESSION["itemsnumber"])){
     $_SESSION["itemsnumber"] = 0;
    
     }
                      
    $number = $_SESSION["itemsnumber"];
    for ($i=1;$i<=$number;$i++){
       if ($_SESSION["item_$i"]==$item);
       unset($_SESSION["item_$i"]);
        $_SESSION["itemsnumber"]--;
       }

}


function AddCart($item){
    if (!isset($_SESSION["itemsnumber"])){
     $_SESSION["itemsnumber"] = 0;
    
     }
                      
    $number = $_SESSION["itemsnumber"];
    $number++;
    $_SESSION["itemsnumber"] = $number; 
    $_SESSION["item_$number"] = $item;

}

function printCart(){
     if (isset($_SESSION["itemsnumber"])){
       $number = $_SESSION["itemsnumber"];
       $total  = 0;
       $arr = array();
     echo "<b>Total Items : $number</b><br/>";
      for ($i=1;$i<=$number;$i++){
       $item = $_SESSION["item_$i"];
       @$arr[$item]++;
       }
       echo "<a href='manage/cart.php'>View Items On Cart</a><br/>";
    echo ("<br/>");
while (list($key, $val) = each($arr)){
      
 $title = getItemAttribute("IT_NAME",$key);
   $price = getItemAttribute("IT_PRICE",$key)*$val;
  $ingredients  = getItemAttribute("IT_DESCRIPTION",$key);
  $total+=$price;
  
    
    
    }
    echo ("------------------------------------<br/>");
    echo ("<b>Total Cart Price : $total $ <br/></b>");
     echo ("------------------------------------<br/>");
     }
     else{
        
     echo "<b>Total Items : 0</b><br/>";

    echo ("------------------------------------<br/>");
    echo ("<b>Total Cart Price : 0 $ <br/></b>");
     echo ("------------------------------------<br/>");
     }

}


function CurrencyConvert($from,$to,$amount){
   $data = file_get_contents ("http://rate-exchange.appspot.com/currency?from=$from&to=$to&q=$amount");
   
   $data = json_decode($data);
   return round($data->v);
}


function checkCart($c){
    $carpet = new Carpet();
    $flag=0;
    if (isset($_COOKIE["itemsnumber"]) ){
       $number = $_COOKIE["itemsnumber"];
      if (isset($_COOKIE["items"]))
       $items = unserialize($_COOKIE['items']);
    for ($i=0;$i<$number;$i++){
    if ($items[$i]==$c)
    $flag=1;
    }
    return $flag;
        
    }
    else{
    setcookie("itemsnumber",0,time()+3600*24);
    header("Location: details.php?c=$c");
    }
}

 function RemoveCategory($id){
   
      global $connection;



 $select = $connection->query("DELETE FROM categories WHERE CAT_ID='$id'");
  $select = $connection->query("DELETE FROM items WHERE IT_CATEGORY='$id'");
 if ($select)
 return 1;
 else 
 return 0;
 }
 
 function RemoveCoupon($fid){
      global $connection;

 $select = $connection->query("DELETE FROM coupons where CO_CODE= '$fid'  ");

   
}

 function RemoveDeposit($fid){
      global $connection;
$id = $_SESSION["id"];
if (IsAdminLoggedIn())
$select = $connection->query("DELETE FROM deposits where DEP_ID= '$fid' ");
else if (IsLoggedIn()){
 $status = getDepositAttribute("DEP_STATUS",$fid);
 if ($status==0 || $status==1){
 $select = $connection->query("DELETE FROM deposits where DEP_ID= '$fid' AND USR_ID='$id'  ");
 }
 else return 0;
 }
if ($select) return 1;
else return 0;
   
}

 function RemoveBank($fid){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("DELETE FROM banks where B_ID= '$fid' ");
if ($select) return 1;
else return 0;
   }
   else return 0;
}

function RemoveItem($fid){
      global $connection;

 $select = $connection->query("DELETE FROM pictures where IT_ID= '$fid'  ");

 $select = $connection->query("DELETE FROM items where IT_ID= '$fid'  ");
   
}

function getSlotList($slot){
      global $connection;

 $select = $connection->query("SELECT SL_ID number from slots WHERE SL_NUMBER = '$slot' AND SL_STATUS='0' ORDER BY SL_CREATE_DATE DESC LIMIT 1200");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $email = getSlotAttribute("SL_EMAIL",$id);
 $name = getSlotAttribute("SL_NAME",$id);
echo "$email:$name\n";
 }
 }
function geTableItems(){
      global $connection;

 $select = $connection->query("SELECT IT_ID number,IT_NAME number2, IT_PRICE number3, IT_DESCRIPTION number4  from items ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 $price = $enregistrement->number3;
 $description = $enregistrement->number4;
 $image = getPictureForItem($id);
  echo "
    <li><img src='upload/$image' alt='' height='20px' widh='20px' /><a href='#' title=''>$name ($price)</a>
						<p><i class='fa fa-map-marker'></i>$description</p>
					<i><a href='EditItems.php?edit=$id'>Edit</a>
						<a href='deleteItem.php?id=$id'>Delete</a></i>
						</li>
  
  ";
}
}


function GenerateMenu(){
      global $connection;
$i=0;
 $select = $connection->query("SELECT CAT_ID number,CAT_NAME number2  from categories WHERE CAT_MENU='0' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
    
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 if ($i==0) echo " <div class='grid-12 ourmenu'>";
  echo "
  <h2>$name</h2>
                <hr/>
  ";
  $i++;
  $select2 = $connection->query("SELECT IT_ID number,IT_NAME number2, IT_PRICE number3, IT_DESCRIPTION number4  from items where IT_CATEGORY = '$id' AND IT_MENU='0'");
  if($select2)  if ($connection->query("SELECT FOUND_ROWS()")->fetchColumn()>0){
   $select2->setFetchMode(PDO::FETCH_OBJ);
 
    while( $enregistrement2 = $select2->fetch() )
{
    if ($i!=0) echo " <div class='grid-12 ourmenu'>";;
    $id2 = $enregistrement2->number;
    $name2 = $enregistrement2->number2;
 $price = $enregistrement2->number3;
 $description = $enregistrement2->number4;
 $image = getPictureForItem($id2);
   echo "
     <h4>
                    <a class='clb-photo' href='upload/$image'>$name2</a><a href='#' id='$id2' class='addcart'> Add To Cart</a>
                    <span>$$price </span>
               
                </h4>
                <p>$description<br/></p>
   </div>
   ";
   
    }
}
else{
    echo "</div>";
}
}
}

function GenerateSpecial(){
      global $connection;
$i=0;
 $select = $connection->query("SELECT CAT_ID number,CAT_NAME number2  from categories WHERE CAT_MENU='1' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
    
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 if ($i==0) echo " <div class='grid-12 ourmenu'>";
  echo "
  <h2>$name</h2>
                <hr/>
  ";
  $i++;
  $select2 = $connection->query("SELECT IT_ID number,IT_NAME number2, IT_PRICE number3, IT_DESCRIPTION number4  from items where IT_CATEGORY = '$id' WHERE IT_MENU='1'");
    if ($connection->query("SELECT FOUND_ROWS()")->fetchColumn()>0){
   $select2->setFetchMode(PDO::FETCH_OBJ);
 
    while( $enregistrement2 = $select2->fetch() )
{
    if ($i!=0) echo " <div class='grid-12 ourmenu'>";;
    $id2 = $enregistrement2->number;
    $name2 = $enregistrement2->number2;
 $price = $enregistrement2->number3;
 $description = $enregistrement2->number4;
 $image = getPictureForItem($id2);
if ($image)
   echo "
     <h4>
                    <a class='clb-photo' href='upload/$image'>$name2</a>
                    <span>$$price</span>
                </h4>
                <p>$description</p>
   </div>
   ";
    }
}
else{
    echo "</div>";
}
}
}


function getFeeds(){
      global $connection;
       require_once('/rss/FeedParser.php');
   $time = time();

   $select = $connection->query("SELECT R_ID number from emailrss WHERE ER_NEXT<=$time ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
        $id = $enregistrement->number;
       $email = getRSSEMAILAttribute("E_ID",$id);
       $time = getRSSEMAILAttribute("ER_TIME",$id);
       $link = getRSSAttribute("R_LINK",$id);
        $cat = getCategoryAttribute("CAT_NAME",getRSSEMAILAttribute("CAT_ID",$id));
        
      
         
        $Parser = new FeedParser();

$Parser->parse($link);

$channels  	= $Parser->getChannels();     
$items     	= $Parser->getItems();        


	foreach($items as $item):
	 echo $item['LINK']."<hr/>$cat<br/>";
	
	 endforeach;
     
    $select = $connection->query("UPDATE emailrss SET R_NEXT=R_NEXT+$time WHERE R_ID='$id' "); 
	
}

}


function geTableCategories(){
      global $connection;

 $select = $connection->query("SELECT CAT_ID number,CAT_NAME number2  from categories ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 
  echo "
    <li><a href='#' title=''>$name</a>
						<p><i class='fa fa-map-marker'></i>Hover To Edit</p>
						
							<i><a href='EditItems.php?edit=$id'>Edit</a>
						<a href='deleteCategory.php?id=$id'>Delete</a></i>
						</li>
  
  ";
}
}

function geTableChat(){
      global $connection;

 $select = $connection->query("SELECT DISTINCT CH_FROM number2,CH_ID number,CH_TIME number3 from chat WHERE CH_FROM!='+14803866195' GROUP BY CH_FROM ORDER BY CH_TIME DESC");
  //$select = $connection->query("SELECT DISTINCT V_NUMBER number2,V_ID number,V_NAME number3 FROM verification WHERE V_VERIFIED='1'");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 $time =FormatDate($enregistrement->number3);
  echo "
    <a href='interact.php?id=$name'>$name (Last Message $time)</a><br/>
  
  ";
}
}


function getChatContent($number){
      global $connection;

 $select = $connection->query("SELECT CH_ID number,CH_FROM number2,CH_TO number3,CH_BODY number4,CH_TIME number5  from chat WHERE CH_FROM='$number' OR CH_TO='$number' ORDER BY CH_TIME ASC");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $from = $enregistrement->number2;
 $to = $enregistrement->number3;
 $body = $enregistrement->number4;
$time = FormatDate($enregistrement->number5);
 if ($from==$number)
  echo "
    <span style='color:green';>[$time] $from -> $body</span><br/>
  
  ";
  else
   echo "
    <span style='color:blue';>[$time] $from -> $body</span><br/>
  
  ";
}
}


function CheckFeeds(){
      global $connection;

 $select = $connection->query("SELECT R_ID number from rss ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 
  echo "
    <li><a href='#' title=''>$name</a>
						<p><i class='fa fa-map-marker'></i>Hover To Edit</p>
						
							<i><a href='EditItems.php?edit=$id'>Edit</a>
						<a href='deleteCategory.php?id=$id'>Delete</a></i>
						</li>
  
  ";
}
}


function getEmailCategories(){
      global $connection;

 $select = $connection->query("SELECT CAT_ID number,CAT_NAME number2 from cat ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 
  echo "
    <option value='$id'>$name</option>
  
  ";
}
}

function getEmailSubCategories($catid){
      global $connection;

 $select = $connection->query("SELECT SCAT_ID number,SCAT_NAME number2 from subcat WHERE CAT_ID='$catid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 
  echo "
    <option value='$id'>$name</option>
  
  ";
}
}


function getEmailSubCategoriesInit(){
      global $connection;

 $select = $connection->query("SELECT SCAT_ID number,SCAT_NAME number2 from subcat WHERE CAT_ID=(SELECT CAT_ID FROM cat WHERE 1 LIMIT 1) ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 
  echo "
    <option value='$id'>$name</option>
  
  ";
}
}


function getSelectCategories(){
      global $connection;

 $select = $connection->query("SELECT CAT_ID number,CAT_NAME number2  from categories ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
    $name = $enregistrement->number2;
 
  echo "
    <option value='$id'>$name</option>
  
  ";
}
}
function getTableItemsMenu(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT IT_ID number from items WHERE IT_MENU='0' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $name = getItemAttribute("IT_NAME",$id);
  $description = getItemAttribute("IT_DESCRIPTION",$id);
  $price = getItemAttribute("IT_PRICE",$id);
  $image = getPictureForItem($id);
  $category = getCategoryAttribute("CAT_NAME",getItemAttribute("IT_CATEGORY",$id));

  echo 
    "<tr><td><b>$name<b></td><td>$description</td><td>$price$</td><td>$category</td><td>$image</td><td><a href='editItem.php?id=$id'>Edit</a> |<a href='deleteitem.php?id=$id'> Remove</a></td></tr>";
  $i++;
  
}
}


function getTableItemsSpecials(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT IT_ID number from items WHERE IT_MENU='1' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $name = getItemAttribute("IT_NAME",$id);
  $description = getItemAttribute("IT_DESCRIPTION",$id);
  $price = getItemAttribute("IT_PRICE",$id);
  $image = getPictureForItem($id);
  $category = getCategoryAttribute("CAT_NAME",getItemAttribute("IT_CATEGORY",$id));

  echo 
    "<tr><td><b>$name<b></td><td>$description</td><td>$price$</td><td>$category</td><td>$image</td><td><a href='editItem.php?id=$id'>Edit</a> |<a href='deleteitem.php?id=$id'> Remove</a></td></tr>";
  $i++;
  
}
}

function getItemsNumberForCategory($id){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT COUNT(IT_ID) number from items WHERE IT_CATEGORY='$id' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    $enregistrement = $select->fetch();
    return $enregistrement->number;
    }


function getTableCategoriesMenu(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT CAT_ID number from categories WHERE CAT_MENU='0' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $name = getCategoryAttribute("CAT_NAME",$id);
  $number = getItemsNumberForCategory($id);


  echo 
    "<tr><td><b>$name<b></td><td>$number</td><td><a href='editCategory.php?id=$id'>Rename</a> |<a href='deleteCategory.php?id=$id'> Remove</a></td></tr>";
  $i++;
  
}
}

function getBanksSelect($lang=false){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT B_ID number,B_NAME number2 from banks ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  if ($lang==2)
  $name = getBANKattribute("B_NAME",$id,$lang);
  else
$name = $enregistrement->number2;


  echo 
    "<option value='$id'>$name</option>
                 ";
  
}
}

 
function getTableCategoriesSpecials(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT CAT_ID number from categories WHERE CAT_MENU='1' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $name = getCategoryAttribute("CAT_NAME",$id);
  $number = getItemsNumberForCategory($id);


  echo 
    "<tr><td><b>$name<b></td><td>$number</td><td><a href='editCategory.php?id=$id'>Rename</a> |<a href='deleteCategory.php?id=$id'> Remove</a></td></tr>";
  $i++;
  
}
}

function getTableUsers(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT USR_ID number from users");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $fname = getUserAttribute("USR_FNAME",$id);
  $lname = getUserAttribute("USR_LNAME",$id);
  $email = getUserAttribute("USR_EMAIL",$id);
  $address = getUserAttribute("USR_ADDRESS",$id);
  $phone = getUserAttribute("USR_PHONE",$id);
  $postalcode = getUserAttribute("USR_POSTCODE",$id);
  $status = getUserAttribute("IS_ADMIN",$id);
  if ($status==0) $status="User";   else $status="Admin";

  echo 
    "<tr><td>$i</td><td>$fname</td><td>$lname</td><td>$email</td><td>$address</td><td>$postalcode</td><td>$phone</td><td>$status</td><td><a href='makeAdmin.php?id=$id'>Make Admin</a> | <a href='removeAdmin.php?id=$id'>Remove Admin</a></td></tr>";
  $i++;
  
}
}


function getNonSentNotifications(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT N_ID number, N_PHONE  number2,N_FLIGHTID number4, N_SENT number3 from notifications WHERE N_SENT ='0'");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $phone =  $enregistrement->number2;
  $sent =  $enregistrement->number3;
  $flight = $enregistrement->number4;
  echo "$id $phone $sent $flight";
   if(getArrivedFromFlightNumber($flight)){
    echo "im here";
    SendSMS("Flight $flight Has Just Arrived To its destination successfully !",$phone);
   UpdateNotification($id,1);
}
  
  
}
}

function getTableDeposits($lang=false){
      global $connection;
$i = 1;
$uid = $_SESSION["id"];
 $select = $connection->query("SELECT DEP_ID number from deposits WHERE USR_ID='$uid'");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $amount = getDepositAttribute("DEP_AMOUNT",$id);
  $curr = getDepositAttribute("DEP_AMOUNT_CURR",$id);
  $status = getDepositAttribute("DEP_STATUS",$id);
  $topay = getDepositAttribute("DEP_TOPAY",$id);
  $comment = getDepositAttribute("DEP_COMMENT",$id);
    $trid = getDepositAttribute("DEP_TRANSACTION_ID",$id);
  $date = FormatDate(getDepositAttribute("DEP_DATE",$id));
  $service = getDepositAttribute("DEP_SERVICE",$id);
   $bank = getBANKattribute("B_NAME",getDepositAttribute("B_ID",$id));
   if ($lang==2) {
    $deletename = "מחיקה";
    $noactionname = "אין פעולות";
    $markaspaidname = "סמן כשולם";
    $declinedname = "נדחה";
    $notpaidname = "טרם שולם";
    $approvedname = "מאושר";
    $pendingreviewname = "ממתין לאישור";
    $na = "לא זמין";
    $float = "right";
   } else {
    $deletename="delete";
    $noactionname = "No Action";
    $markaspaidname = "Mark As Paid";
    $declinedname = "Declined";
    $notpaidname ="Not Paid";
    $approvedname ="Approved";
    $pendingreviewname = "Pending Review";
    $na = "N/A";
    $float="left";
   }
  if ($comment=="null") $comment=$na;
   if ($trid=="null") $trid=$na;
  $actions = "<button type='button' onclick=\"location.href = 'depositpaid.php?c=$id';\" class='btn btn-success btn-xs' style='float:$float'  >$markaspaidname</button> <button type='button' onclick=\"location.href = 'deletedeposit.php?c=$id';\" class='btn btn-danger btn-xs' style='float:$float' >$deletename</button>";
  if ($service==0) $service="<img src='img/lite_small.png' width='80%'/> ";   else $service="<img src='img/plus_small.png' width='80%'/>";
   if ($status==1) $actions="<button type='button' onclick=\"location.href = 'deletedeposit.php?c=$id';\" class='btn btn-danger btn-xs' >$deletename</button>";
  else if ($status!=0) $actions ="<button type='button' class='btn btn-default btn-xs' >$noactionname</button>";
 if ($status==0){ $status="<span class=\"label label-primary\">$notpaidname</span>"; }    else if ($status==1) $status="<span class=\"label label-warning\">$pendingreviewname</span>"; else if ($status==2) $status="<span class=\"label label-success\">$approvedname</span>"; else if ($status==3) $status="<span class=\"label label-danger\">$declinedname</span>" ;
  $class="class=\"text-right\"";
 $curr = "$curr";
  echo 
    "<tr><td >$date</td><td >$service</td><td>$bank</td><td $class>$amount $curr</td><td $class>$topay NIS</td><td >$status</td><td >$trid</td><td >$comment</td><td width='150px' >$actions</td></tr>";
  $i++;
  
}
}


function getTableDepositsALL($statustype){
      global $connection;
$i = 1;
$uid = $_SESSION["id"];

 $select = $connection->query("SELECT DEP_ID number from deposits WHERE DEP_STATUS='$statustype'");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $amount = getDepositAttribute("DEP_AMOUNT",$id);
  $curr = getDepositAttribute("DEP_AMOUNT_CURR",$id);
  $status = getDepositAttribute("DEP_STATUS",$id);
  $topay = getDepositAttribute("DEP_TOPAY",$id);
  $comment = getDepositAttribute("DEP_COMMENT",$id);
  $trid = getDepositAttribute("DEP_TRANSACTION_ID",$id);
  $date = FormatDate(getDepositAttribute("DEP_DATE",$id));
  $service = getDepositAttribute("DEP_SERVICE",$id);
  $bank = getBANKattribute("B_NAME",getDepositAttribute("B_ID",$id));
  $uid =  getDepositAttribute("USR_ID",$id);
  $useremail = getUserAttribute("USR_EMAIL",$uid);
  $useremail="<a href='profile.php?c=$uid'>$useremail</a>";
  if ($comment=="null") $comment="N/A";
   if ($trid=="null") $trid="N/A";
  $actions = "<a href=depositpaid.php?c=$id>Mark As Paid</a> | <a href=deletedeposit.php?c=$id>Delete</a>";
  $actions = "<button type='button' onclick=\"location.href = 'depositpaid.php?c=$id';\" class='btn btn-success btn-xs' style='float:left'  >Mark As Paid</button> <button type='button' onclick=\"location.href = 'deletedeposit.php?c=$id';\" class='btn btn-danger btn-xs' style='float:left' >Delete</button>";
  if ($service==0) $service="<img src='../img/lite_small.png' width='80%' />";   else $service="<img src='../img/plus_small.png' width='80%' />";
 
  if ($statustype==0) $actions="<button type='button' onclick=\"location.href = 'deletedeposit.php?c=$id';\" class='btn btn-danger btn-xs' style='float:left' >Delete</button>";
else  $actions="<button type='button'  onclick=\"location.href = 'editdeposit.php?c=$id';\" class='btn btn-primary btn-xs' style='float:left'  >Update Status</button><button type='button' onclick=\"location.href = 'deletedeposit.php?c=$id';\" class='btn btn-danger btn-xs' style='float:left' >Delete</button>  ";
 if ($status==0){ $status="<span class=\"label label-primary\">Not Paid</span>"; }    else if ($status==1) $status="<span class=\"label label-warning\">Pending Review</span>"; else if ($status==2) $status="<span class=\"label label-success\">Approved</span>"; else if ($status==3) $status="<span class=\"label label-danger\">Rejected</span>" ;
 if ($i%2==0) $class="class=\"text-right\"';"; else $class="class=\"text-right\"";
  echo 
    "<tr><td >$date</td><td >$service</td><td >$useremail</td><td>$bank</td><td $class>$amount $curr</td><td $class>$topay NIS</td><td >$status</td><td >$trid</td><td >$comment</td><td width='150px' >$actions</td></tr>";
  $i++;
  
}
}

function getTableCards($uid,$lang=false){
      global $connection;
$i = 1;
if (IsLoggedIn() && !IsAdminLoggedIn()){
if ($uid!= $_SESSION["id"]) return 0;
$uid = $_SESSION["id"];
}

 $select = $connection->query("SELECT C_ID number from cards WHERE USR_ID='$uid' ORDER BY C_TIME DESC");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $depid = getCardAttribute("DEP_ID",$id);
  $account = getCardAttribute("C_ACCOUNT",$id);
  $cardbalance = GetCardBalanceAmount($account);
    $cardbalancecurrency = GetCardBalanceCurrency($account);
  $deptransaction = getDepositAttribute("DEP_TRANSACTION_ID",$depid);
  $cardnumber = getCardAttribute("C_CARD_NUMBER",$id);
  $expiry = getCardAttribute("C_EXPIRY_DATE",$id);
  $cvv2 = getCardAttribute("C_CVV2",$id);
  $activationCode = getCardAttribute("C_ACTIVATION_CODE",$id);
  $activationurl = getCardAttribute("C_ACTIVATION_URL",$id);
  $amountloaded = getDepositAttribute("DEP_AMOUNT",$depid);
  $currencyloaded = getDepositAttribute("DEP_AMOUNT_CURR",$depid);
  $balance = GetCardBalanceAmount($account);
    $curr = GetCardBalanceCurrency($account);
  $date = FormatDate(getCardAttribute("C_TIME",$id));
  $service = getCardAttribute("C_TYPE",$id);
   $uid =  getCardAttribute("USR_ID",$id);
  $useremail = getUserAttribute("USR_EMAIL",$uid);
  $useremail="<a href='profile.php?c=$uid'>$useremail</a>";
 if ($lang==2)
 $actions="<button type='button'  onclick=\"location.href = 'transactions.php?c=$id';\" class='btn btn-primary btn-xs' style='float:left'  >צפייה בפעולות</button>";
 
 else
  $actions="<button type='button'  onclick=\"location.href = 'transactions.php?c=$id';\" class='btn btn-primary btn-xs' style='float:left'  >View Transactions</button>";
 
    if ($service==0) $service="<img src='liteimagenoinfo.php?c=$id' width='80%' />";   else $service="<img src='plusimagenoinfo.php?c=$id' width='80%' />";
 if ($i%2==0) $class="class=\"text-right\"';"; else $class="class=\"text-right\"";
  echo 
    "<tr><td >$date</td><td >$deptransaction</td><td style='width:20%'>$service</td><td>$cardnumber</td><td>$expiry</td><td>$cvv2</td><td $class>$amountloaded $currencyloaded</td><td $class>$cardbalance $cardbalancecurrency</td><td>$actions</td></tr>";
  $i++;
   
}
}


function getTableCardsTransactions($card,$uid){
      global $connection;
$i = 1;
$query = "SELECT C_ACCOUNT number, DEP_ID number2 from cards WHERE C_ID='$card' AND USR_ID='$uid'  ORDER BY C_TIME DESC";
if (IsLoggedIn() && !IsAdminLoggedIn()){
if ($uid!= $_SESSION["id"]) return 0;
$uid = $_SESSION["id"];
}
if (IsAdminLoggedIn())
$query = "SELECT C_ACCOUNT number, DEP_ID number2 from cards WHERE C_ID='$card'  ORDER BY C_TIME DESC";

 $select = $connection->query($query);
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
      $acc = $enregistrement->number;
       $depid = $enregistrement->number2;
       $depdate = getDepositAttribute("DEP_DATE",$depid);
    $transactions  = GetTransactions($acc);
if (!is_int($transactions)){
    $transactionJson = json_decode(GetTransactionsList($transactions));
foreach ($transactionJson as $transactions){
    foreach ($transactions as $transaction){
        $datestamp  = strtotime(str_replace('.', '-', $transaction->dateTime));
        if ($i==1)
        $diff = $depdate - $datestamp;
      //echo"dt is $datestamp + $diff";
        $diff = ceil($diff/3600)*3600;
        $datestamp+=$diff;
        //  echo"= $datestamp";
        $date = FormatDate($datestamp);
        $transactionID = $transaction->transactionId;
        $amount = "$transaction->amount $transaction->currency";
        $description =  $transaction->transactionDescription;
        if ($i%2==0) $class="class=\"text-right\"';"; else $class="class=\"text-right\"";
  echo 
    "<tr><td >$date</td><td >$transactionID</td><td>$amount</td><td>$description</td></tr>";
  $i++;
  
   }
}
}
else 
 return 0;
  // Affichage d'un des champs
   
   
   
}
}

function getTableCardsALL(){
      global $connection;
$i = 1;
$uid = $_SESSION["id"];

 $select = $connection->query("SELECT C_ID number from cards");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $depid = getCardAttribute("DEP_ID",$id);
   $account = getCardAttribute("C_ACCOUNT",$id);
  $cardbalance = GetCardBalanceAmount($account);
    $cardbalancecurrency = GetCardBalanceCurrency($account);
  $deptransaction = getDepositAttribute("DEP_TRANSACTION_ID",$depid);
  $cardnumber = getCardAttribute("C_CARD_NUMBER",$id);
  $expiry = getCardAttribute("C_EXPIRY_DATE",$id);
  $cvv2 = getCardAttribute("C_CVV2",$id);
  $activationCode = getCardAttribute("C_ACTIVATION_CODE",$id);
  $activationurl = getCardAttribute("C_ACTIVATION_URL",$id);
  $amountloaded = getDepositAttribute("DEP_AMOUNT",$depid);
  $currencyloaded = getDepositAttribute("DEP_AMOUNT_CURR",$depid);
  $date = FormatDate(getCardAttribute("C_TIME",$id));
  $service = getCardAttribute("C_TYPE",$id);
   $uid =  getCardAttribute("USR_ID",$id);
  $useremail = getUserAttribute("USR_EMAIL",$uid);
  $useremail="<a href='profile.php?c=$uid'>$useremail</a>";
 $actions="<button type='button'  onclick=\"location.href = 'transactions.php?c=$id';\" class='btn btn-primary btn-xs' style='float:left'  >View Transactions</button>";
 
    if ($service==0) $service="<img src='../img/lite_small.png' width='80%' />";   else $service="<img src='../img/plus_small.png' width='80%' />";
 if ($i%2==0) $class="class=\"text-right\"';"; else $class="class=\"text-right\"";
  echo 
    "<tr><td >$date</td><td >$useremail</td><td >$deptransaction</td><td>$service</td><td>$cardnumber</td><td>$expiry</td><td>$cvv2</td><td $class>$amountloaded $currencyloaded</td><td>$activationurl</td><td >$activationCode</td><td $class>$cardbalance $cardbalancecurrency</td><td>$actions</td></tr>";
  $i++;
   
}
}


function getTableUsersAll(){
      global $connection;
$i = 1;


 $select = $connection->query("SELECT USR_ID number from users");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $email = getUserAttribute("USR_EMAIL",$id);
  $rank = getUserAttribute("USR_ADMIN",$id);
  $status = getUserAttribute("USR_ACTIVE",$id);
 $date = FormatDate(getUserAttribute("USR_TIME",$id));
  $deposits = getDepositsNumberForUser($id);
    $cards = getCardsNumberForUser($id);
 $profile = checkProfileComplete($id);
 $activate = "<a href=edituser.php?c=$id&a=1>Activate/Unban</a>";
   $ban = "<a href=edituser.php?c=$id&a=2>Ban</a>";
   $delete = "<a href=edituser.php?c=$id&a=3>Delete</a>";
      $makeadmin = "<a href=edituser.php?c=$id&a=4>Make Admin</a>";
       $removeadmin = "<a href=edituser.php?c=$id&a=5>Remove Admin</a>";
       $actions="";
      if ($status==0 || $status==2) $actions.=$activate." | "; else if ($status==1) $actions.=$ban." | ";
     if ($rank==0) $actions.=$makeadmin." | "; else if ($rank==1)$actions.=$removeadmin." | ";
     $actions .= $delete;
 if ($profile==null)$profile="<a href=../profile.php?c=$id>Not Complete</a>"; else $profile="<a href=../profile.php?c=$id>Complete</a>";
  if ($status==0) $status="Pending Email Verification"; else if ($status==2) $status="Banned"; else if($status==1) $status="Active";
   if ($rank==0) $rank="User"; else if ($rank==1) $rank="Admin";
   
 
if ($i%2==0) $class="class='hilit';"; else $class="";
  echo 
    "<tr><td $class>$date</td><td $class>$email</td><td $class>$profile</td><td $class>$deposits</td><td $class>$cards</td><td $class>$rank</td><td $class>$status</td><td $class>$actions</td></tr>";
  $i++;
  
}

}
function getCountries(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT COUNTRY_ID number, COUNTRY_NAME number2 from country ORDER BY COUNTRY_NAME ASC");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
    $name = $enregistrement->number2;
  $id = $enregistrement->number;
  echo "<option value='$id'>$name</option>";
  
  
  }
  
  }


function getStates($country){
      global $connection;

 $select = $connection->query("SELECT STATE_ID number, STATE_NAME number2 from state WHERE STATE_ID LIKE '$country.%' ORDER BY STATE_NAME ASC");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
    $name = $enregistrement->number2;
  $id = $enregistrement->number;
  echo "<option value='$id'>$name</option>";
  
  
  }
  
  }
  
  
  function getCities($country){
      global $connection;

 $select = $connection->query("SELECT CITY_NAME number, CITY_ID number2 from city WHERE CITY_ID LIKE '$country.%' ORDER BY CITY_NAME ASC");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
    $name = $enregistrement->number;
  $id = $enregistrement->number2;
  echo "<option value='$id'>$name</option>";
  
  
  }
  
  }

function getTableCoupons(){
      global $connection;
$i = 1;
 $select = $connection->query("SELECT CO_CODE number from coupons");
   $select->setFetchMode(PDO::FETCH_OBJ);
    while( $enregistrement = $select->fetch() )
{
  // Affichage d'un des champs
  $id = $enregistrement->number;
  $code = getCouponAttribute("CO_CODE",$id);
  $value = getCouponAttribute("CO_VALUE",$id);
  $times = getCouponAttribute("CO_TIMES",$id);
  

  echo 
    "<tr><td>$i</td><td>$code</td><td>$value</td><td>$times</td><td><a href='editCoupon.php?id=$id'>Edit Coupon</a> | <a href='deleteCoupon.php?id=$id'>Remove Coupon</a></td></tr>";
  $i++;
  
}
}


function getTableCart(){
      global $connection;
$i = 1;
       $number = $_SESSION["itemsnumber"];
       $total  = 0;
       $arr = array();
      for ($i=1;$i<=$number;$i++){
       $item = $_SESSION["item_$i"];
       @$arr[$item]++;
       }
while (list($key, $val) = each($arr)){
      
 $title = getItemAttribute("IT_NAME",$key);
   $price = getItemAttribute("IT_PRICE",$key)*$val;
  $ingredients  = getItemAttribute("IT_DESCRIPTION",$key);
  $image  = getPictureForItem($key);
  $total+=$price;
  echo '
  	<li>
							<div class="cart-product">
								<a href="removeItemCart.php?item='.$key.'" title=""><i class="fa fa-times"></i></a>
								<h6>'.$title.'</h6>
								<img src="../upload/'.$image.'" alt="" />
							</div>
							<div class="cart-price">
								<h6 class="red">'.$price/$val.' $</h6>
							</div>
							<div class="cart-quantity">
							<center><h6 class="red">'.$val.'</h6></center>
							</div>
							<div class="cart-total">
								<h6>'.$price.' $</h6>
							</div>
						</li>
						
  
  ';
  
    }
     $_SESSION["carttotal"]= $total;
  

}

function getTransactionDate($oid){
      global $connection;

 $select = $connection->query("SELECT TRA_DATE number from transactions  where TRA_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
  return date('m/d/Y - H:i:s', $enregistrement->number);
    else return 0;
}

function getOrderDate($oid){
      global $connection;

 $select = $connection->query("SELECT OD_DATE number from orders  where OD_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
  return date('m/d/Y - H:i:s', $enregistrement->number);
    else return 0;
}

function FormatDate($time){
    return date('m/d/Y - H:i:s', $time);
}
function getOrderHR($oid){
      global $connection;

 $select = $connection->query("SELECT OD_HR number from orders  where OD_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderUsername($oid){
      global $connection;

 $select = $connection->query("SELECT USR_ID number from orders  where OD_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return getUsername($enregistrement->number);
    else return 0;
}

function getTransactionAmount($oid){
      global $connection;

 $select = $connection->query("SELECT TRA_AMT number from transactions  where TRA_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getTransactionUsername($oid){
      global $connection;

 $select = $connection->query("SELECT USR_ID number from transactions  where TRA_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return getUsername($enregistrement->number);
    else return 0;
}

function getTransactionCurrency($oid){
      global $connection;

 $select = $connection->query("SELECT TRA_CURRENCY number from transactions  where TRA_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getTransactionEmail($oid){
      global $connection;

 $select = $connection->query("SELECT TRA_EMAIL number from transactions  where TRA_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getTransactionPayerID($oid){
      global $connection;

 $select = $connection->query("SELECT TRA_PAYERID number from transactions  where TRA_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderCountry($oid){
      global $connection;

 $select = $connection->query("SELECT OD_COUNTRY number from orders  where OD_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderAmount($oid){
      global $connection;

 $select = $connection->query("SELECT OD_AMOUNT number from orders  where OD_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderStatus($oid){
      global $connection;

 $select = $connection->query("SELECT OD_STATUS number from orders  where OD_ID='$oid' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderVideoLink($oid){
      global $connection;

 $select = $connection->query("SELECT VID_URL number from videos  where VID_ID=(SELECT VID_ID from orders where OD_ID='$oid') ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderVideoStartViews($oid){
      global $connection;

 $select = $connection->query("SELECT VID_VIEWS number from videos  where VID_ID=(SELECT VID_ID from orders where OD_ID='$oid') ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return $enregistrement->number;
    else return 0;
}

function getOrderVideoCurrentViews($oid){
    $url = getOrderVideoLink($oid);
    
parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
$video_ID =  $my_array_of_vars['v'];   
    $JSON = @file_get_contents("https://gdata.youtube.com/feeds/api/videos/{$video_ID}?alt=json");

   // echo "https://gdata.youtube.com/feeds/api/videos/{$video_ID}?alt=json-";

    $JSON_Data = json_decode($JSON,true);

    //echo "result: $JSON </br><br/>";

    $views = $JSON_Data['entry']['yt$statistics']['viewCount'];

   // echo $views."<br/>";

    if($views == "") {

        $views = "Invalid Video Link";

    }

    return $views;
}

function getVideoCurrentViewsFromURL($url){
    
    
parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
$video_ID =  $my_array_of_vars['v'];   
    $JSON = @file_get_contents("https://gdata.youtube.com/feeds/api/videos/{$video_ID}?alt=json");

   // echo "https://gdata.youtube.com/feeds/api/videos/{$video_ID}?alt=json-";

    $JSON_Data = json_decode($JSON,true);

    //echo "result: $JSON </br><br/>";

    $views = $JSON_Data['entry']['yt$statistics']['viewCount'];

   // echo $views."<br/>";

    if($views == "") {

        $views = "Invalid Video Link";

    }

    return $views;
}

function getSlotCount($slot,$num){
      global $connection;

 $select = $connection->query("SELECT count(SL_ID) number from slots  where SL_NUMBER= '$slot' AND SL_STATUS='0'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number >$num){
   return getLatestLeadForSlot($slot);

    }
    
    else return false;
    
}

function getCouponAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from coupons  where CO_CODE= '$fid'  ");
 if ($connection->query("SELECT FOUND_ROWS()")->fetchColumn()>0){
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
    }
    else return false;
}

function getCategoryAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from cat  where CAT_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getSubCategoryAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from subcat  where CAT_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getItemAttribute($attr,$fid){
      global $connection;
 
 $select = $connection->query("SELECT $attr number from items  where IT_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return "N/A";
}

function getSlotAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from slots  where SL_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getAffiliateAttributeByUID($attr,$uid){
      global $connection;

 $select = $connection->query("SELECT $attr number from affiliates  where GR_UID= '$uid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getChatAttribute($attr,$uid){
      global $connection;

 $select = $connection->query("SELECT $attr number from chat  where CH_ID= '$uid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getChatAttributeByNumber($attr,$uid){
      global $connection;

 $select = $connection->query("SELECT $attr number from chat  where CH_FROM= '$uid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getBANKattribute($attr,$uid,$lang=false){
      global $connection;

 $select = $connection->query("SELECT $attr number from banks  where B_ID= '$uid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   $name = $enregistrement->number;
   
if($lang==2 && $attr=="B_NAME"){
     if (strpos(strtolower($name),'leumi') !== false) 
    $name = "בנק לאומי";
    else if (strpos(strtolower($name),'hapoalim') !== false) 
    $name = "בנק הפועלים";
    else if (strpos(strtolower($name),'mizrahi') !== false) 
    $name = "בנק מזרחי";
    else if (strpos(strtolower($name),'discount') !== false) 
    $name = "בנק דיסקונט\מרכנתיל";
    else if (strpos(strtolower($name),'postal') !== false) 
    $name = "בנק הדואר";
    else if (strpos(strtolower($name),'international') !== false)
    $name = "הבנק הבינלאומי הראשון"; 
   
   return $name; 
}
else
    return $enregistrement->number;
  
}

  

function getCommissionPercentage(){
      global $connection;

 $select = $connection->query("SELECT FE_PERCENTAGE number from fees  where FE_ID= '1'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
  
}

function getCardsNumberForUser($id){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT COUNT(C_ID) number from cards  where USR_ID= '$id'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
  }
  else{
    return 0;
  }
  
}

function getDepositsNumberForUser($id){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT COUNT(DEP_ID) number from deposits  where USR_ID= '$id'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
  }
  else{
    return 0;
  }
  
}

function getCardAnnualFee(){
      global $connection;

 $select = $connection->query("SELECT FE_CARD_FEE number from fees  where FE_ID= '1'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
  
}

function calculateDepositTotal($amount,$currency,$card){
      global $connection;
$factor =file_get_contents("http://finance.yahoo.com/d/quotes.csv?e=.csv&f=c4l1&s=".$currency."ILS=X");
$factor = explode(",",$factor);
$factor = $factor[1];
$rate = $factor;
$factor = $amount*$factor;
$commission = $factor*getCommissionPercentage();
if ($card==0)
return ceil(($factor*getCommissionPercentage())+$factor);
  else return ceil(($factor*getCommissionPercentage())+$factor+getCardAnnualFee());
  
}

function getAnnualCardFee(){
      global $connection;

 $select = $connection->query("SELECT FE_CARD_FEE number from fees  where FE_ID= '1'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
  
}

function getMYBANKIDfromBank($uid){
      global $connection;

 $select = $connection->query("SELECT MB_ID number from mybank  where B_ID= '$uid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getMYBANKattribue($attr,$uid){
      global $connection;

 $select = $connection->query("SELECT $attr number from mybank  where MB_ID= '$uid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getChatLatestDateByNumber($number){
      global $connection;

 $select = $connection->query("SELECT CH_TIME number from chat  where CH_FROM= '$number' ORDER BY CH_TIME DESC LIMIT 1  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getRSSEMAILAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from emailrss  where R_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getRSSAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from rss  where R_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function getArchiveAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from archive  where R_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function checkProfileComplete($id=false){
      global $connection;
      if (!$id)
$id = $_SESSION["id"];
 $select = $connection->query("SELECT LEAST(USR_FNAME,USR_LNAME,USR_PHONE,USR_DOB_DAY,USR_DOB_MONTH,USR_GENDER,USR_DOB_YEAR) number FROM users_personal WHERE USR_ID='$id'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    $result =  $enregistrement->number;
    
 if ($result==null) return $result;
 else{
    $select = $connection->query("SELECT LEAST(USR_ADDRESS,USR_CITY,USR_POSTCODE) number FROM users_address WHERE USR_ID='$id'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    $result =  $enregistrement->number;
    if ($result==null) return $result;
    else{
       $select = $connection->query("SELECT LEAST(GOV_ID,GOVT_ID,GOV_ISSUE_DAY,GOV_ISSUE_MONTH,GOV_ISSUE_YEAR,GOV_EXPIRY_DAY,GOV_EXPIRY_MONTH,GOV_EXPIRY_YEAR,GOV_FILE) number FROM users_personal WHERE USR_ID='$id'");
   if ($result==null) return $result;
   else return 1;
    }
 }


}

function getVerificationAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from verification  where V_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    return $enregistrement->number;
  
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getNextAffiliate(){
      global $connection;

 $select = $connection->query("SELECT AFF_ID number from affiliates  where AFF_STATUS ='1'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   $id = $enregistrement->number;

   $num = getGRSubscribersNumber(getAffiliateAttribute("GR_API",$id));

 
   if ($num<24900){
   SetNextAffiliateStatus();
    return $enregistrement->number;
    }
    else{
SetNextAffiliateStatus();
       return false;
    }
}

function SetNextAffiliateStatus(){
      global $connection;

 $select = $connection->query("SELECT COUNT(AFF_ID) number1, AFF_ID number from affiliates  WHERE AFF_STATUS= '1' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
if ($enregistrement->number1==1){
    $id =  $enregistrement->number;
   $select = $connection->query("UPDATE affiliates SET AFF_STATUS = '0'  where AFF_ID= '$id'  ");
   
 $select = $connection->query("SELECT COUNT(AFF_ID) number1, AFF_ID number from affiliates  where AFF_STATUS= '0' AND AFF_ID>'$id' LIMIT 1  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();

    if ($enregistrement->number1==1){
    $id = $enregistrement->number;
       $select = $connection->query("UPDATE affiliates SET AFF_STATUS = '1'  where AFF_ID= '$id'  ");
    }
    else{
           $select = $connection->query("UPDATE affiliates SET AFF_STATUS = '1'  LIMIT 1  ");
    }
    }
    else{
         $select = $connection->query("UPDATE affiliates SET AFF_STATUS = '0'");
         
         $select = $connection->query("UPDATE affiliates SET AFF_STATUS = '1'  LIMIT 1  ");
    }
}




function getUserAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getDepositAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from deposits  where DEP_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getCardAttribute($attr,$fid){
      global $connection;
//echo "SELECT $attr number from cards where C_ID= '$fid' ";
 $select = $connection->query("SELECT $attr number from cards where C_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function CardFormat($card){
    $str = "";
    for ($i=1;$i<=strlen($card);$i++){
        $index = $i-1;
        if ($i%4==0) $str.= "$card[$index] ";
        else $str.= $card[$index];
    }
    return $str;
}

function getUserPersonalAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from users_personal  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getUserAddressAttribute($attr,$fid){
      global $connection;
 $select = $connection->query("SELECT $attr number from users_address  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getUserGovAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from users_gov_information where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getUserGoverAttribute($attr,$fid){
      global $connection;

 $select = $connection->query("SELECT $attr number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getCountryFromIP(){
      global $connection;
$ip = $_SERVER['REMOTE_ADDR'];
echo $ip."<br/>";
$ip = explode(".",$ip);
$ip = $ip[0]*16777216 + $ip[1]*65536 + $ip[2]*256 + $ip[3];

 $select = $connection->query("SELECT IP_COUNTRY number from iptocountry  where IP_INTEGER_FROM<= $ip AND IP_INTEGER_TO>=$ip  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    
    else return 0;
}

function getKeywordSuggestionsFromGoogle($keyword) {
    $keywords = array();
    $data = file_get_contents('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode($keyword));
    if (($data = json_decode($data, true)) !== null) {
        $keywords = $data[1];
    }

    return $keywords;
}




function getCountryABFromIP(){
      global $connection;
$ip = $_SERVER['REMOTE_ADDR'];
 $select = $connection->query("SELECT IP_COUNTRY_AB number from iptocountry  where IP_FROM<= '$ip' AND IP_TO>='$ip'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    
    else return 0;
}


function getActivationCode($code){
      global $connection;

 $select = $connection->query("SELECT COUNT(USR_ACTIVATION) number from users  where USR_ACTIVATION= '$code'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    
    else return 0;
}

function getEmail($fid){
      global $connection;

 $select = $connection->query("SELECT USR_EMAIL number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    
    else return 0;
}


function processAccount($email,$name,$ip,$ua,$vps){
      global $connection;
$time = time();
 $select = $connection->query("UPDATE slots SET SL_STATUS= '1',SL_SUBMIT_DATE='$time',SL_SUBMIT_IP='$ip',SL_SUBMIT_UA='$ua',SL_SUBMIT_VPS='$vps' WHERE SL_EMAIL='$email' AND SL_NAME='$name'");
$select = $connection->query("INSERT INTO archive (SELECT * from slots WHERE SL_EMAIL='$email' AND SL_NAME='$name')");
 $select = $connection->query("DELETE from slots WHERE SL_EMAIL='$email' AND SL_NAME='$name'");
}

function UpdateProxyRack($value){
      global $connection;

 $select = $connection->query("UPDATE settings SET S_PROXYRACK = '$value'");
 
 
}

function UpdateDeposit($id,$decision,$comment){
      global $connection;
if (IsAdminLoggedIn()){
        $old = getDepositAttribute("DEP_STATUS",$id);
    if ($old==1 && $decision==2){
        $currency = getDepositAttribute("DEP_AMOUNT_CURR",$id);
        $amount = getDepositAttribute("DEP_AMOUNT",$id);
       // $amount = 5;
        if ($currency=="EUR"){
            $resp = IssueLiteCard($amount,1);
           //var_dump($resp);
         //  die();
        if (!is_int($resp)){
             
            $user = getDepositAttribute("USR_ID",$id);
            $account = explode("=",$resp[0]);
            $account= urldecode($account[1]);
            
            $activationcode = explode("=",$resp[1]);
            $activationcode= urldecode($activationcode[1]);
            
            $activationURL = explode("=",$resp[2]);
            $activationURL= urldecode($activationURL[1]);
            
            $CardNumber = explode("=",$resp[3]);
            $CardNumber= urldecode($CardNumber[1]);
            
            $cvv2 = explode("=",$resp[4]);
            $cvv2= urldecode($cvv2[1]);
            
            $expiry = explode("=",$resp[5]);
            $expiry= urldecode($expiry[1]);
            
            $san = explode("=",$resp[9]);
            $san= urldecode($san[1]);
            
            $transactionID = explode("=",$resp[10]);
            $transactionID= urldecode($transactionID[1]);
            
        $select = $connection->query("UPDATE deposits SET DEP_STATUS = '$decision', DEP_COMMENT='$comment' WHERE DEP_ID='$id'");   
       InsertNewCard($id,$user,$account,$CardNumber,$expiry,$cvv2,$san,$transactionID,$activationcode,$activationURL,$transactionID,0);
        
       } 
        else return $resp;
 }
 else  if ($currency=="USD"){
      $resp = IssueLiteCard($amount,2);
  
    if (!is_int($resp)){
            $user = getDepositAttribute("USR_ID",$id);
            
            $user = getDepositAttribute("USR_ID",$id);
            $account = explode("=",$resp[0]);
            $account= urldecode($account[1]);
            
            $activationcode = explode("=",$resp[1]);
            $activationcode= urldecode($activationcode[1]);
            
            $activationURL = explode("=",$resp[2]);
            $activationURL= urldecode($activationURL[1]);
            
            $CardNumber = explode("=",$resp[3]);
            $CardNumber= urldecode($CardNumber[1]);
            
            $cvv2 = explode("=",$resp[4]);
            $cvv2= urldecode($cvv2[1]);
            
            $expiry = explode("=",$resp[5]);
            $expiry= urldecode($expiry[1]);
            
            $san = explode("=",$resp[9]);
            $san= urldecode($san[1]);
            
            $transactionID = explode("=",$resp[10]);
            $transactionID= urldecode($transactionID[1]);
            
    $select = $connection->query("UPDATE deposits SET DEP_STATUS = '$decision', DEP_COMMENT='$comment' WHERE DEP_ID='$id'");   
       InsertNewCard($id,$user,$account,$CardNumber,$expiry,$cvv2,$san,$transactionID,$activationcode,$activationURL,$transactionID,0);
       } 
        else return $resp;
        
 }
 


 }
 else
   $select = $connection->query("UPDATE deposits SET DEP_STATUS = '$decision', DEP_COMMENT='$comment' WHERE DEP_ID='$id'");   
 
  if ($select) 
    return true;
  
 else return 0;
 }
 else return 0;
}

function UpdateBank($id,$name,$number){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("UPDATE banks SET B_NAME = '$name', B_NUMBER='$number' WHERE B_ID='$id'");
 if ($select) return 1;
 else return 0;
 }
 else return 0;
}

function UpdateAccount($email,$password,$cpassword){
      global $connection;
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) return -4;
      else if ($email=="" || $password=="") return -3;
      else if ($password!=$cpassword) return -1;
      else if ($email!=getUserAttribute("USR_EMAIL",$_SESSION["id"])&&(CheckEmail($email))) return -2;
      
if (IsLoggedIn()){
    $password = md5($password);
    $id = $_SESSION["id"];
 $select = $connection->query("UPDATE users SET USR_EMAIL = '$email', USR_PASSWORD='$password' WHERE USR_ID='$id'");
 if ($select) return 1;
 else return 0;
 }
 else return 0;
}

function UpdateUserStatus($id,$action){
      global $connection;
      if ($action==1 || $action==2){
      if ($action==1) $status = "1";
      else if ($action==2) $status="2";
      $query = "UPDATE users SET USR_ACTIVE = '$status' WHERE USR_ID='$id'";
      }
      else if ($action==3)
       $query = "DELETE FROM users WHERE USR_ID='$id'";
       else {
        if ($action==4) $status=1;
        else if ($action==5) $status=0;
       $query = "UPDATE users SET USR_ADMIN = '$status' WHERE USR_ID='$id'";
       }
      
if (IsAdminLoggedIn()){
 $select = $connection->query($query);
 if ($select) return 1;
 else return 0;
 }
 else return 0;
}

function UpdateMyBank($id,$name,$number){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("UPDATE mybank SET MB_ACCOUNT_NUMBER='$name',MB_BRANCH_NUMBER='$number' WHERE B_ID = '$id'");
 if ($select) return 1;
 else return 0;
 }
 else return 0;
}


function UpdateMyFees($name,$number){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("UPDATE fees SET  FE_PERCENTAGE='$name',FE_CARD_FEE='$number' WHERE FE_ID='1'");
 if ($select) return 1;
 else return 0;
 }
 else return 0;
}



function UpdateSubmissionLink($type,$value,$slot){
      global $connection;
if ($type==1)
 $select = $connection->query("UPDATE settings SET S_AWEBER = '$value' WHERE S_SLOT='$slot'");
 else
 $select = $connection->query("UPDATE settings SET S_GR = '$value'  WHERE S_SLOT='$slot'");
 
}

function getProxyrack(){
      global $connection;

 $select = $connection->query("SELECT S_PROXYRACK number from settings ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getProxyrackList(){
      global $connection;

 $select = $connection->query("SELECT S_PROXYRACK number from settings ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   { 
    for ($i=1;$i<=1300;$i++)
     echo "$enregistrement->number\n";
  }
}

function getSubmissionLink($type,$slot){
      global $connection;
if ($type==1)
 $select = $connection->query("SELECT S_AWEBER number from settings WHERE S_SLOT='$slot' ");
 else
 $select = $connection->query("SELECT S_GR number from settings WHERE S_SLOT='$slot' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function RemoveUser($fid){
      global $connection;

 $select = $connection->query("DELETE FROM users where USR_ID= '$fid'  ");
   
}

function getPictureForItem($fid){
      global $connection;

 $select = $connection->query("SELECT PIC_NAME number from pictures  where IT_ID= '$fid'  ");
   if ($connection->query("SELECT FOUND_ROWS()")->fetchColumn()>0){
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  
    return $enregistrement->number;

    }
    else{
        return "notset.jpg";
    }
}


function getNmPrice($fid){
      global $connection;

 $select = $connection->query("SELECT USR_RATENM number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getUsaPrice($fid){
      global $connection;

 $select = $connection->query("SELECT USR_RATEUS number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getHrNmPrice($fid){
      global $connection;

 $select = $connection->query("SELECT USR_RATEHRNM number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getTotalCardsIssued(){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT COUNT(C_ID) number FROM cards");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
    }
}

function getTotalUsersNumber(){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT COUNT(USR_ID) number FROM users");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
    }
}

function getTotalDepositsNumber($status){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT COUNT(DEP_ID) number FROM deposits WHERE DEP_STATUS='$status' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
    }
}

function getTotalDepositsMoney($status){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT SUM(DEP_TOPAY) number FROM deposits WHERE DEP_STATUS='$status' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return  number_format((float)$enregistrement->number, 2, '.', '');
    else return 0;
    }
}

function getTotalDepositsProfit($status){
      global $connection;
if (IsAdminLoggedIn()){
 $select = $connection->query("SELECT SUM(DEP_COMMISSION) number FROM deposits WHERE DEP_STATUS='$status' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
   return  number_format((float)$enregistrement->number, 2, '.', '');
    else return 0;
    }
}



function getHrUsaPrice($fid){
      global $connection;

 $select = $connection->query("SELECT USR_RATEHRUS number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getLatestUser(){
        global $connection;

 $select = $connection->query("SELECT USR_ID number from users ORDER BY USR_ID DESC LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getLatestGeneric($attr,$table){
        global $connection;

 $select = $connection->query("SELECT $attr number from $table ORDER BY $attr DESC LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getLatestRSSid(){
        global $connection;

 $select = $connection->query("SELECT R_ID number from rss ORDER BY R_ID DESC LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getLatestLeadForSlot($slot){
        global $connection;

 $select = $connection->query("SELECT SL_ID number from slots where SL_NUMBER= '$slot' AND SL_STATUS='0' ORDER BY SL_CREATE_DATE ASC LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}

function getLatestVidID($fid){
      global $connection;

 $select = $connection->query("SELECT VID_ID number from videos  where USR_ID= '$fid' ORDER BY VID_ID DESC LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getLastLoginIP($fid){
      global $connection;

 $select = $connection->query("SELECT USR_IP number from users  where USR_ID= '$fid'  ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
}


function getNumberUsers(){
      global $connection;

 $select = $connection->query("SELECT COUNT(USR_ID)number FROM USERS");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   return $enregistrement->number;
}


function InsertNewNotification($phone,$flight){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("INSERT INTO notifications VALUES
 (null,'$phone','$flight','0') ");

    if ($select) return 1; else return 0;
    
}


function InsertNewCategory($name){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("INSERT INTO cat VALUES
 (null,'$name') ");

    if ($select) return 1; else return 0;
    
}

function InsertNewSubCategory($name,$cat){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("INSERT INTO subcat VALUES
 (null,'$cat','$name') ");

      if ($select) return 1; else return 0;
    
}
function InsertNewImageForItem($item,$image){
   
      global $connection;

$start = time();


 $select = $connection->query("INSERT INTO pictures VALUES
 (null, '$image','$item') ");
 }



function InsertNewItem($name,$description,$price,$category,$menu,$image=false){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];




 $select = $connection->query("INSERT INTO items VALUES
 (null,'$name','$description','$price','$category','$menu') ");

 $select = $connection->query("SELECT IT_ID number from items ORDER BY IT_ID DESC LIMIT 1 ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number !=null)
    return $enregistrement->number;
    else return 0;
    
    
}

function UpdateUser($fname,$lname,$email,$address,$postalcode,$phone){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

$id = $_SESSION["id"];

 $select = $connection->query("UPDATE users SET USR_FNAME='$fname', USR_LNAME='$lname',USR_EMAIL='$email',USR_ADDRESS='$address',USR_POSTCODE='$postalcode',USR_PHONE='$phone' WHERE USR_ID='$id' ");

    
    
}

function ConfirmDeposit($depid,$transactionid){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

$id = $_SESSION["id"];

 $select = $connection->query("UPDATE deposits SET DEP_TRANSACTION_ID='$transactionid', DEP_STATUS='1' WHERE DEP_ID='$depid' AND USR_ID='$id' ");
if ($select) return 1;
else return 0;
    
    
}

function UpdateFirstLastName($fname,$lname,$id){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];


//echo "UPDATE users_personal SET USR_FNAME='$fname', USR_LNAME='$lname',USR_DOB_DAY='$day',USR_DOB_MONTH='$month',USR_DOB_YEAR='$year',USR_GENDER='$gender',USR_PHONE='$phone' WHERE USR_ID='$id' ";
 $select = $connection->query("UPDATE users_personal SET USR_FNAME='$fname', USR_LNAME='$lname' WHERE USR_ID='$id' ");

    if ($select) return 1;
    else return 0;
    
}

function UpdateUserPersonal($fname,$lname,$day,$month,$year,$gender,$phone,$passedid=false){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];


$id = $_SESSION["id"];

if (IsAdminLoggedIn() && $passedid) $id = $passedid;

//echo "UPDATE users_personal SET USR_FNAME='$fname', USR_LNAME='$lname',USR_DOB_DAY='$day',USR_DOB_MONTH='$month',USR_DOB_YEAR='$year',USR_GENDER='$gender',USR_PHONE='$phone' WHERE USR_ID='$id' ";
 $select = $connection->query("UPDATE users_personal SET USR_FNAME='$fname', USR_LNAME='$lname',USR_DOB_DAY='$day',USR_DOB_MONTH='$month',USR_DOB_YEAR='$year',USR_GENDER='$gender',USR_PHONE='$phone' WHERE USR_ID='$id' ");

    if ($select) return 1;
    else return 0;
    
}

function UpdateUserAddress($address,$city,$postcode,$passedid=false){
   
      global $connection;
$time = time();


$id = $_SESSION["id"];
if (IsAdminLoggedIn() && $passedid) $id = $passedid;
 $select = $connection->query("UPDATE users_address SET USR_ADDRESS='$address', USR_CITY='$city',USR_POSTCODE='$postcode' WHERE USR_ID='$id' ");

    if ($select) return 1;
    else return 0;
    
}

function UpdateUserGovInformation($govid,$govtid,$day,$month,$year,$day2,$month2,$year2,$file,$passedid=false){
   
      global $connection;
$time = time();

$id = $_SESSION["id"];
if (IsAdminLoggedIn() && $passedid) $id = $passedid;
 $select = $connection->query("UPDATE users_gov_information SET GOV_ID='$govid', GOVT_ID='$govtid', GOV_ISSUE_DAY='$day', GOV_ISSUE_MONTH='$month', GOV_ISSUE_YEAR='$year', GOV_EXPIRY_DAY='$day2', GOV_EXPIRY_MONTH='$month2', GOV_EXPIRY_YEAR='$year2',GOV_FILE='$file' WHERE USR_ID='$id' ");

    //echo "UPDATE users_gov_information SET GOV_ID='$govid', GOVT_ID='$govtid', GOV_ISSUE_DAY='$day', GOV_ISSUE_MONTH='$month', GOV_ISSUE_YEAR='$year', GOV_EXPIRY_DAY='$day2', GOV_EXPIRY_MONTH='$month2', GOV_EXPIRY_YEAR='$year2',GOV_FILE='$file' WHERE USR_ID='$id' ";
    if ($select) return 1;
    else return 0;
}

function InsertNewUser($fname,$lname,$username,$email,$password,$cpassword,$dob,$phone,$company,$address,$postalcode,$country,$state,$city,$paypal){
   
      global $connection;
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return -1;
      else if ($password!=$cpassword) return -2;

$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

$password = md5($password);
$accid = '';
do{
    
$accid = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1) . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
}
while(CheckAccID($accid)==1);
     $random_hash = substr(md5(uniqid(rand(), true)), 0,28); 

 $select = $connection->query("INSERT INTO users VALUES
 (null,'$accid','$fname','$lname','$username','$email','$password','$dob','$phone','$company','$address','$postalcode','$country','$state','$city','$paypal','-1','$random_hash') ");
if($select)
{
    

 $to      = "$email";
$subject = 'Activate Your Account';
$message = "Dear $fname,\nPlease click the link below to complete verification:
Verify My Email Address\n
http://ppl.google-analytics.us/activation/?c=$random_hash
If you did not sign up for this account you can ignore this email and the account will be deleted.
Kind regards\n,
The PayPerLead Team";
$headers = 'From: Activation@google-analytics.us' . "\r\n" .
    'Reply-To: f.jouti@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
    return 1;
}
else return 0;
    
}

function InsertNewUserAccount($email,$password,$cpassword,$fname,$lname){
   
      global $connection;
      if (empty($email) || empty($fname) || empty($lname)) return -7;

    else  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return -1;
      else if ($password!=$cpassword) return -2;
    else if(CheckEmail($email)) return -4;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

$password = md5($password);
/*$accid = '';
do{
    
$accid = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1) . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
}
while(CheckAccID($accid)==1);
  */
     $random_hash = substr(md5(uniqid(rand(), true)), 0,28); 

 $select = $connection->query("INSERT INTO users VALUES
 (null,'$email','$password','$random_hash','0','0','$time') ");
 $id = getLatestUser();
if($select)
{
     $select = $connection->query("INSERT INTO users_personal VALUES
 ('$id',null,null,null,null,null,null,null) ");
 
   $select = $connection->query("INSERT INTO users_address VALUES
 ('$id',null,null,null) ");


  $select = $connection->query("INSERT INTO users_gov_information VALUES
 ('$id',null,null,null,null,null,null,null,null,null) ");
 
 UpdateFirstLastName($fname,$lname,$id);
 $to      = "$email";
$subject = 'Activate Your Account';
$message = "Dear User,\nPlease click the link below to complete verification:
Verify My Email Address\n
http://i-g.co/en/activation/?c=$random_hash
If you did not sign up for this account you can ignore this email and the account will be deleted.
Kind regards\n,
The FastCard Team";
$headers = 'From: Activation@i-g.co' . "\r\n" .
    'Reply-To: Activation@i-g.co' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
    return 1;
}
else return 0;
    
}

function UpdateCartWithCoupon($coupon){
   
      global $connection;
$time = time();

$id  = getCouponAttribute("CO_ID",$coupon);
if ($id)
{
    $number = getCouponAttribute("CO_TIMES",$coupon);
    if ($number>0){
 $select = $connection->query("UPDATE coupons SET CO_TIMES=CO_TIMES-1 where CO_ID='$id' ");
    return getCouponAttribute("CO_VALUE",$coupon);
    }
    else return false;
}
else return false;
    
    
}


function UpdateImageForItem($id,$img){
   
      global $connection;
      $time = time();

if (getPictureForItem($id)!="notset.jpg")

 $select = $connection->query("UPDATE pictures SET PIC_NAME='$img' WHERE IT_ID='$id' ");

    else return false;
    
}

function RemoveUserAdmin($id){
   
      global $connection;
      $time = time();



 $select = $connection->query("UPDATE users SET IS_ADMIN='0' where USR_ID='$id' ");

    
    
}

function MakeUserAdmin($id){
   
      global $connection;
      $time = time();



 $select = $connection->query("UPDATE users SET IS_ADMIN='1' where USR_ID='$id' ");

    
    
}

function UpdateCategory($id,$title){
   
      global $connection;
      $time = time();



 $select = $connection->query("UPDATE categories SET CAT_NAME='$title' where CAT_ID='$id' ");

    
    
}

function UpdateItem($id,$title,$description,$price,$category){
   
      global $connection;
      $time = time();



 $select = $connection->query("UPDATE items SET IT_NAME='$title', IT_DESCRIPTION='$description', IT_PRICE='$price',IT_CATEGORY='$category' where IT_ID='$id' ");

    
    
}

function InsertNewBank($name,$number){
   
      global $connection;
$time = time();

if (IsAdminLoggedIn()){
 $select = $connection->query("INSERT INTO banks VALUES
 (null,'$name','$number' ) ");
if ($select) {
    $id =  getLatestGeneric("B_ID","banks");
     $select = $connection->query("INSERT INTO mybank VALUES
 (null,'$id',null,null ) ");
 if ($select) return $id;
 else return 0;
    }
else return 0;
}
else return 0;
    
}

function InsertNewDeposit($amount,$currency,$bank,$card){
   global $minimumdeposit;
      global $connection;
$time = time();
if ($amount<$minimumdeposit) return -1;
$id = $_SESSION["id"];
$topay = calculateDepositTotal($amount,$currency,$card);
$factor =file_get_contents("http://finance.yahoo.com/d/quotes.csv?e=.csv&f=c4l1&s=".$currency."ILS=X");
$factor = explode(",",$factor);
$factor = $factor[1];
$rate = $factor;
$factor = $amount*$factor;
$commission = $factor*getCommissionPercentage();

 $select = $connection->query("INSERT INTO deposits VALUES
 (null,'$id','$time','$amount','$currency','$topay','$commission','$rate','0','$bank','null','$card','null' ) ");
if ($select) return getLatestGeneric("DEP_ID","deposits");
else return 0;
    
}


function InsertNewCard($dep,$user,$account,$cardnumber,$expiry,$cvv2,$san,$traceid,$activationcode,$activationurl,$transaction,$type){
   
      global $connection;
$time = time();

 $select = $connection->query("INSERT INTO cards VALUES
 (null,'$dep','$user','$account','$cardnumber','$expiry','$cvv2','$san','$traceid','$time','$activationcode','$activationurl','$transaction','$type' ) ");
if ($select) return getLatestGeneric("C_ID","cards");
else return 0;
    
}




function InsertNewTransaction($username,$amt,$currency,$fname,$lname,$email,$payerid,$countrycode){
   
      global $connection;
$time = time();




 $select = $connection->query("INSERT INTO transactions VALUES
 (null,'$username','$time','Paypal','$amt','$currency','$fname','$lname','$email','$payerid','$countrycode' ) ");

    
}

function InsertNewChatMessage($msgid,$from,$to,$body){
   
      global $connection;
$time = time();




 $select = $connection->query("INSERT INTO chat VALUES
 (null,'$msgid','$from','$to','$body','$time' ) ");

    
}


function AddCredits($fid,$amount){
   
      global $connection;
$time = time();



echo ("UPDATE users SET USR_CREDITS=USR_CREDITS+$amount where USR_ID='$fid'");

 $select = $connection->query("UPDATE users SET USR_CREDITS=USR_CREDITS+$amount where USR_ID='$fid'");
 

    
}

function RemoveCredits($fid,$amount){
   
      global $connection;
$time = time();




 $select = $connection->query("UPDATE users SET USR_CREDITS=USR_CREDITS-$amount where USR_ID='$fid'");
 

    
}



function InsertNewVideo($uid,$url){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

$views = getVideoCurrentViewsFromURL($url);


 $select = $connection->query("INSERT INTO videos VALUES
 (null,'$uid','$url','$views' ) ");

    
}

function InsertNewPhone($name,$phone){
   
      global $connection;
     if (strlen($phone)!=10) return 2;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$activation = generateRandomString(5);

 $select = $connection->query("INSERT INTO verification VALUES
 (null,'$name','$phone','$activation','$ip','$time','0') ");

if ($select) {
try{
    SendSMS("Verification Code : $activation",$phone);
    return 1;
    }
    catch(exception $ex){
        return 3;
    }
    
}
else return 0;
    
}

function verifyPhone($phone,$code){
    $code1 = GetPhoneCode($phone);
    if ($code1==$code) {
        UpdatePhoneStatus(1,$phone);
        $_SESSION["activated"]=1;
     return 1;   
    }
    else return 0;
}

function IsLoggedIn(){
    if (!isset($_SESSION["id"])) return 0;
    else return $_SESSION["id"];
}

function IsAdminLoggedIn(){
    if (!isset($_SESSION["id"])) return 0;
    else {
       if (CheckLoginAdminByID($_SESSION["id"]))
       return $_SESSION["id"];
       else 
       return 0; 
    }
}

function CheckLogin($username,$password){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$password = md5($password);
if (strlen($username)==0 || strlen($password)==0) return -1;
//echo "SELECT COUNT(USR_ID) number, USR_ID number2 FROM users where (USR_EMAIL='$username' OR USR_USERNAME='$username') AND USR_PASSWORD='$password'";
 $select = $connection->query("SELECT COUNT(USR_ID) number, USR_ID number2 FROM users where USR_EMAIL='$username'  AND USR_PASSWORD='$password' AND USR_ACTIVE='1'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number==1){
   $_SESSION["id"] = $enregistrement->number2;
   return 1;
   }
    else
    return 0;
    
}

function CheckLoginAdmin($username,$password){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$password = md5($password);

//echo "SELECT COUNT(USR_ID) number, USR_ID number2 FROM users where (USR_EMAIL='$username' OR USR_USERNAME='$username') AND USR_PASSWORD='$password'";
 $select = $connection->query("SELECT COUNT(USR_ID) number, USR_ID number2 FROM users where USR_EMAIL='$username'  AND USR_PASSWORD='$password' AND USR_ACTIVE='1' AND USR_ADMIN='1'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number==1){
   $_SESSION["id"] = $enregistrement->number2;
   return 1;
   }
    else
    return 0;
    
}

function CheckLoginAdminByID($id){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];


//echo "SELECT COUNT(USR_ID) number, USR_ID number2 FROM users where (USR_EMAIL='$username' OR USR_USERNAME='$username') AND USR_PASSWORD='$password'";
 $select = $connection->query("SELECT COUNT(USR_ID) number from users where USR_ID='$id'  AND USR_ADMIN='1'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number==1){

   return 1;
   }
    else
    return 0;
    
}



function isAdmin($id){
   
      global $connection;


 $select = $connection->query("SELECT IS_ADMIN number FROM users where USR_ID='$id'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   return $enregistrement->number;
  
    
}

function CheckAccID($acc){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("SELECT COUNT(ACC_ID)number FROM users WHERE ACC_ID='$acc'");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number==1)
   return 1;
    else
    return 0;
    
}

function CheckEmail($email){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("SELECT COUNT(USR_ID)number FROM users where USR_EMAIL='$email' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number==1)
   return 1;
    else
    return 0;
    
}

function CheckUsername($email){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("SELECT COUNT(USR_ID)number FROM users where USR_USERNAME='$email' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
   if ($enregistrement->number==1)
   return 1;
    else
    return 0;
    
}

function GetCurrentPassword($id){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];



 $select = $connection->query("SELECT USR_PASSWORD number FROM users where USR_ID='$id' ");
   $select->setFetchMode(PDO::FETCH_OBJ);
   $enregistrement = $select->fetch();
  
    return $enregistrement->number;
    
}

function UpdatePassword($id,$password){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$password = md5($password);

 $select = $connection->query("UPDATE users set USR_PASSWORD = '$password' where USR_ID='$id' ");
  
    
}

function ActivateUser($activation){
   
      global $connection;




 $select = $connection->query("UPDATE users set USR_ACTIVE = '1',USR_ACTIVATION='FJFJFJFJ00' where USR_ACTIVATION='$activation' ");
  
    
}


function UpdatePhoneStatus($status,$phone){
   
      global $connection;




 $select = $connection->query("UPDATE verification set V_VERIFIED = '$status' where V_NUMBER='$phone' ");
;
    
}

function SendSMS($message,$to){

require_once('twilio-php/Services/Twilio.php'); // Loads the library
 // $to = substr($to, 1);

$account_sid = 'ACea887378f0c6462e461acf3a973491a9'; 
$auth_token = '9bd3b657189fa2719a408ad09035fd45'; 
$client = new Services_Twilio($account_sid, $auth_token); 
 
$client->account->messages->create(array( 
	'To' => "$to", 
	'From' => "+14803866195", 
	'Body' => "$message", 
    )); 
}

function UpdateLastLogin($id){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];


 $select = $connection->query("UPDATE users set USR_LASTLOGIN = '$time',USR_IP='$ip' where USR_ID='$id' ");
  
    
}


function UpdateEmail($id,$password){
   
      global $connection;
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];


 $select = $connection->query("UPDATE users set USR_EMAIL = '$password' where USR_ID='$id' ");
  
    
}


function InsertNewEntry($rss,$cat,$subcat,$frequency){
       if (!isset($_SESSION["rssemail"])) die();
      global $connection;
      $time = time();
    switch ($frequency){
        case 1:
        $frequency = 24*3600;
        break;
        case 2:
        $frequency = 7*24*3600;
        break;
        case 3:
        $frequency = 3.5*24*3600;
        break;
    }
    $next = time()+$frequency;
    $email = $_SESSION["rssemail"];
    $id = InsertNewRss($rss,$cat,$subcat);
  $select = $connection->query("INSERT INTO emailrss VALUES
 ('null','$id','$email','$frequency','$time' )");
    if ($select){
        return 1;
        }
    

else{
 return 0;
}
}

function InsertNewRss($link,$cat,$subcat){
   
      global $connection;
$time = time();

//echo "INSERT INTO affiliates VALUES
// (null,'$webform','$api','$time','0' ";
  
  $select = $connection->query("INSERT INTO rss VALUES
 (null,'$link','$cat','$subcat' )");
    if ($select){
        return getLatestRSSid();
        }
    

else{
 return 0;
}
}



function InsertNewAffiliate($api,$webform,$uid){
   
      global $connection;
$time = time();

//echo "INSERT INTO affiliates VALUES
// (null,'$webform','$api','$time','0' ";
  
  $select = $connection->query("INSERT INTO affiliates VALUES
 (null,'$webform','$api','$uid','$time','0' )");
    if ($select){
        echo "1";
        }
    

else{
 echo "0";
}
}

function InsertNewLead($email,$name){
   
      global $connection;
$time = time();
$arr =  array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
$k = array_rand($arr);
$slot = $arr[$k];
$id = getSlotCount($slot,30000);

if ($id){
     $select = $connection->query("INSERT INTO archive
SELECT * FROM slots WHERE SL_ID='$id' ");
$oldemail = getSlotAttribute("SL_EMAIL",$id);
  $select = $connection->query("DELETE FROM slots WHERE SL_ID='$id' ");
  
  $select = $connection->query("INSERT INTO slots VALUES
 (null,'$slot','$email','$name','$time','0','0','0','0','0') ");
    if ($select){
        echo "Success : Pushed To Slot </b>$slot</b><br/>";
        echo "Older Email Replaced : <b>$oldemail</b><br/>";
        }
    
}
else{
 $select = $connection->query("INSERT INTO slots VALUES
 (null,'$slot','$email','$name','$time','0','0','0','0','0') ");
    if ($select)
        echo "Success : Pushed To Slot <b>$slot</b>";
    }
}


function InsertNewHistory($fid,$friendid,$count){
   
      global $connection;
$time = time();

 $select = $connection->query("INSERT INTO HISTORY VALUES
 (null,(SELECT USR_ID FROM USERS WHERE USR_FID ='$fid'),'$friendid','$time','$count') ");
    
}

function UpdateNotification($id,$status){
   
      global $connection;
$time = time();

 $select = $connection->query("UPDATE notifications SET N_SENT='1' WHERE N_ID='$id' ");
    
}

function UpdateCoupon($id,$code,$value,$times){
   
      global $connection;
$time = time();

 $select = $connection->query("UPDATE coupons SET CO_CODE='$code', CO_TIMES ='$times',CO_VALUE='$value' WHERE CO_ID='$id' ");
    
}

function InsertNewCoupon($code,$value,$times){
   
      global $connection;
$time = time();

 $select = $connection->query("INSERT INTO coupons VALUES
 (null,'$code','$times','$value') ");
    
}




?>