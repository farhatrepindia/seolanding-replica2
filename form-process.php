<?php
    include "config.php"; 
    date_default_timezone_set('Asia/Kolkata'); 
	$datenow = date('d/m/Y H:i:s');
    $errorMSG = $name = $email = $phone = $message = $success = "";
 
   
    $con = mysqli_connect($host, $user, $pass, $db);
    if (mysqli_connect_errno())
     {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
     }

    if (isset($_POST)) 	{
		
		 function test_input($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);

            return $data;   

        }
// NAME
if (empty($_POST["name"])) {
    $errorMSG = "Name is required ";
} else {
     $name = test_input($_POST['name']);
    if(!preg_match("/^[a-zA-Z ]*$/", $name)){
        $errorMSG = "Only letters and spaces are allowed in name";
    }
}

// EMAIL
if (empty($_POST["email"])) {
    $errorMSG = "Email is required ";
} else {
  $email = test_input($_POST['email']);
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errorMSG = "Invalid email format";
        }
} 

// MESSAGE
 $phone = test_input($_POST['phone']);

/*if (empty($_POST["phone"])) {
    $errorMSG = "Phone is required ";
} else {
     $phone = test_input($_POST['phone']);

    if(!preg_match("/^[0-9]{10}+$/", $phone)){
        $errorMSG ="Enter a valid phone number";
    }
} */

if (empty($_POST["message"])) {
    $errorMSG = "Message is required ";
} else {
     $message = test_input($_POST['message']);
	 //$reg = '/<script\\b[^>]*>(.*?)<\\/script>/i';
	 $reg = '/[a-z0-9.!,&@\-\(\)\{\}\[\]% ]/i';
	 $matches = preg_match_all($reg, $_POST['message'],$matches);

			if($matches < strlen($_POST['message'])){
				$errorMSG ="Special Characters not allowed here!" ;
			}
} 

if (empty($_POST["packages"])) {
    $package = "GET FREE AUDIT";
} else {
	$package = $_POST["packages"]; 
}


   if( $errorMSG == ""){
    $sql = "INSERT INTO seo_landing (name, phone, email, message, submitted_date, package) VALUES ('$name', '$phone', '$email', '$message', '$datenow', '$package')";
        
    $query = mysqli_query($con, $sql);
    if (!$query)
    {
 
     die('Error: ' . mysqli_error($con));
    }

 
   

   
    $headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$rec_headers  = 'MIME-Version: 1.0' . "\r\n";
$rec_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  
    $email_to = "info@repindia.com";
	
	
	$from = "info@repindia.com";
    $email_subject = "Repindia SEO Landing - ".$email;
	$rec_subject = "Thank You";
	$rec_message = "<html><body>";
	$rec_message .= "Dear Sir/Madam,<br><br>";
	$rec_message .= "Thank you for contacting us someone from our team will connect with you shortly.<br><br>";

	$rec_message .= "We looking forward to connect with you soon.<br><br>";
	$rec_message .= "Regards,<br>SEO Team Repindia";
	$rec_message .= "</body></html>";
 
 // Error fuction 

  
    function clean_string($string) {
    $bad = array("content-type","bcc:","to:","cc:","href");
    return str_replace($bad,"",$string);
    }
	  $email_message = "<h2><u>Repindia SEO Landing</h2></br>The details provided by the enquirer are as mentioned below-<br><br>";
	  $email_message .= "<strong>First Name  </strong><strong> : </strong>".clean_string($name)."<br>";
	  $email_message .= "<strong>Phone  </strong><strong> : </strong>".clean_string($phone)."<br>";
	  $email_message .= "<strong>Email Id  </strong><strong> : </strong>".clean_string($email)."<br><br>";
	  $email_message .= "<strong>Message  </strong><strong> : </strong>".clean_string($message)."<br><br>";
	  $email_message .= "<strong>Package enquiry </strong><strong> : </strong>".clean_string($package)."<br><br>";
   
    /* $email_message .= "utm_source: ".clean_string($utm_source)."\n";
    $email_message .= "utm_medium: ".clean_string($utm_medium)."\n";
    $email_message .= "utm_campaign: ".clean_string($utm_campaign)."\n"; */


 
// create email headers
    $headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$email."\r\n" .
    'X-Mailer: PHP/' . phpversion();
	$rec_headers .= 'From: '.$from ."\r\n".
    'Reply-To: '.$email_to."\r\n" .
    'X-Mailer: PHP/' . phpversion();

    // send mail
    $success =  @mail($email_to, $email_subject, $email_message, $headers); 
    $rec_success =  @mail($email, $rec_subject, $rec_message, $rec_headers); 
	
	  }
    // redirect to success page
   // header('location:thankyou.php');
// redirect to success page
if ($success && $errorMSG == ""){
    echo "success";
 }else{
     if($errorMSG == ""){
         echo "Something went wrong :(, mail not sent";
     } else {
         echo $errorMSG;
        // header('location:thankyou.php');
     }
 }
}else{
    echo "Oops!! Something went wrong :(";
}

?>