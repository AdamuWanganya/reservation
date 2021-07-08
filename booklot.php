<?php
session_start();
include('config.php');
require_once('AfricasTalkingGateway.php');

$car = $_POST['car'];
$lot = $_POST['lot'];
$uid = $_SESSION['log']['useruid'];
$otp1 = mt_rand(100000,999999);
$otp2 = mt_rand(100000,999999);

	$from = "carparking@gmail.com";
	$email = $_SESSION['log']['email'];
	$recipient = $email;
	$message = "Message via Car Parking System. This is your OTP1 (Enter while you park your car) ".$otp1." . This is your OTP2 (Enter while you leave the Parking Lot) ".$otp2." ." ;
	$headers = 'From:' . $from;

 
	$sql ="SELECT contact FROM user WHERE email='$email'";
	 $result = $con->query($sql);
	  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $contact = $row['contact'];
      }
      # code...
    }

// Specify your authentication credentials
$username   = "Adamu";
$apikey     = "06a29e378e3ee527a9f577720a346d087d387ddaf50cbebebdd86acab3ecb894";

// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)
//$recipients = $_POST['contact'];
//$names=['Vee'];

//loop through that and send the message

    $recipient=$contact;
    $message = "Message via Car Parking System. This is your OTP1 (Enter while you park your car) ".$otp1." . This is your OTP2 (Enter while you leave the Parking Lot) ".$otp2." ." ;
    
// Create a new instance of our awesome gateway class
$gateway    = new AfricasTalkingGateway($username, $apikey);


try 
{ 
  // Thats it, hit send and we'll take care of the rest. 
  $results = $gateway->sendMessage($recipient, $message);
			
  foreach($results as $result) {
    // status is either "Success" or "error message"
    echo " Number: " .$result->number;
    echo " Status: " .$result->status;
    echo " MessageId: " .$result->messageId;
    echo " Cost: "   .$result->cost."\n";
  

  mysqli_query($con,"INSERT INTO logtable (useruid, lotname, carno, otp1, otp2) VALUES ('$uid', '$lot', '$car', '$otp1', '$otp2') ");
  mysqli_query($con,"UPDATE lot SET status='Ongoing Booking' WHERE lotname='$lot' ");
  header("location:verify.php");
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while sending: ".$e->getMessage();
}

// DONE!!! 


	
?>