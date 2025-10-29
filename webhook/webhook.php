<?php
include_once '../application/libraries/CMT5Request.php';
include_once '../init.php';
define('ConfigData',$applicationsInfo);

$cmtRequest=new CMT5Request();


/*date_default_timezone_set('Asia/Kolkata');*/
date_default_timezone_set('Europe/London');
$txn_id = $_POST['txn_id'];
//$txn_id ="CPGJ3VBSEDU3MBJ2JUWTRNWURD";
$c_date = date("Y-m-d H:i:s");
/*===================================*/    
$servername =ConfigData['servername'];
$username = ConfigData['username'];
$password = ConfigData['password'];
$dbname = ConfigData['dbname'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.* FROM payments p where gateway_id = '$txn_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $email = $row["email"];
    $order_currency = $row["to_currency"]; //BTC
    $order_total = $row["amount"]; //BTC
    $enteredamount = $row['entered_amount'];
    $mt5_login_id = $row['mt5_login_id'];
    $status_frm_db = $row['status'];
    $user_id = $row['user_id'];
    $role =  $row['role'];
  }
} else {
  echo "0 results";
} 
/*===================================*/ 

/*-----------------get private key-----------------*/
$PrivateKey_query = "SELECT value_name FROM basetable where key_name = 'PRIVATE_KEY'";
$result_privatekey = $conn->query($PrivateKey_query);

if ($result_privatekey->num_rows > 0) {
  // output data of each row
  while($row_privatekey = $result_privatekey->fetch_assoc()) {
    $privatekey = $row_privatekey["value_name"];
  }
} 
/*------------------------------------------------*/

/*-----------------get public key-----------------*/
$PublicKey_query = "SELECT value_name FROM basetable where key_name = 'PUBLIC_KEY'";
$result_publickey = $conn->query($PublicKey_query);

if ($result_publickey->num_rows > 0) {
  // output data of each row
  while($row_publickey = $result_publickey->fetch_assoc()) {
    $publickey = $row_publickey["value_name"];
  }
} 
/*------------------------------------------------*/

/*-----------------get merchant id-----------------*/
$merchantId_query = "SELECT value_name FROM basetable where key_name = 'MERCHANT_ID'";
$result_merchantId = $conn->query($merchantId_query);

if ($result_merchantId->num_rows > 0) {
  // output data of each row
  while($row_merchantId = $result_merchantId->fetch_assoc()) {
    $merchantId = $row_merchantId["value_name"];
  }
} 
/*------------------------------------------------*/

/*-----------------get secret IPN-----------------*/
$secretIpn_query = "SELECT value_name FROM basetable where key_name = 'SECRET_IPN'";
$result_secretIpn = $conn->query($secretIpn_query);

if ($result_secretIpn->num_rows > 0) {
  // output data of each row
  while($row_secretIpn = $result_secretIpn->fetch_assoc()) {
    $secretIpn = $row_secretIpn["value_name"];
  }
} 
/*------------------------------------------------*/

/*-----------------get DEBUG Email-----------------*/
$debugEmail_query = "SELECT value_name FROM basetable where key_name = 'DEBUG_EMAIL'";
$result_debugEmail = $conn->query($debugEmail_query);

if ($result_debugEmail->num_rows > 0) {
  // output data of each row
  while($row_debugEmail = $result_debugEmail->fetch_assoc()) {
    $debugEmail = $row_debugEmail["value_name"];
  }
} 
/*------------------------------------------------*/

/*----------Get Single User-----------------*/
$getUserQuery = "SELECT unique_id,parent_id FROM users where user_id ='$user_id'";
$result_UserKeykey = $conn->query($getUserQuery);

if ($result_UserKeykey->num_rows > 0) {
  // output data of each row
  while($row_Userkey = $result_UserKeykey->fetch_assoc()) {
    $userUniqueID   = $row_Userkey["unique_id"];
    $userParentD    = $row_Userkey["parent_id"];
  }
}

/*-----------------------------------------------*/


require "CoinPaymentsAPI.php";
$coin = new CoinPaymentsAPI();
$coin->Setup("$privatekey","$publickey");

$merchant_id = "$merchantId";
$ipn_secret = "$secretIpn";
$debug_email = "$debugEmail";



function edie($error_msg)
{
    global $debug_email;
    $report =  "ERROR : " . $error_msg . "\n\n";
    $report.= "POST DATA\n\n";
    foreach ($_POST as $key => $value) {
        $report .= "|$k| = |$v| \n";
    }
    mail($debug_email, "Payment Error", $report);
    die($error_msg);
}

if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
    edie("IPN Mode is not HMAC");
}

if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
    edie("No HMAC Signature Sent.");
}

$request = file_get_contents('php://input');
if ($request === false || empty($request)) {
    edie("Error in reading Post Data");
}

if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($merchant_id)) {
    edie("No or incorrect merchant id.");
}

$hmac =  hash_hmac("sha512", $request, trim($ipn_secret));
if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
    edie("HMAC signature does not match.");
}

$amount1 = floatval($_POST['amount1']); //IN USD
$amount2 = floatval($_POST['amount2']); //IN BTC
$currency1 = $_POST['currency1']; //USD
$currency2 = $_POST['currency2']; //BTC
$status = intval($_POST['status']);

if ($currency2 != $order_currency) {
    edie("Currency Mismatch");
}

if ($amount2 < $order_total) {
    edie("Amount is lesser than order total");
}

    

if (($status >= 100 || $status == 2) && ($status_frm_db <> 'Confirmed')) {
    
    
    // Payment is complete
    
    $sql = "UPDATE payments SET status='1',created_at='$c_date' WHERE gateway_id = '$txn_id'";
    $date = date('d-m-Y');
    if ($conn->query($sql) === TRUE) {
        $requestItem=array(
            'remark'=>'DepositByCoinPayment',
            'enterAmount'=>$enteredamount,
            'mt5_login_id'=>$mt5_login_id,
            );
        $cmtRequest->depositAmount($requestItem);
      echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }
    
  
} else if ($status < 0) {
    // Payment Error
    $sql = "UPDATE payments SET status='2',created_at='$c_date' WHERE gateway_id = '$txn_id'";

    if ($conn->query($sql) === TRUE) {
      echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }
} else if ($status == 0){
    // Payment Pending
    $sql = "UPDATE payments SET status='0',created_at='$c_date' WHERE gateway_id = '$txn_id'";

    if ($conn->query($sql) === TRUE) {
      echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }
}
die("IPN OK");
$conn->close();
