<?php 
require_once('phpmailer/PHPMailerAutoload.php');

$email = $_POST['user_email'];

$user = 'kosovskiy006@gmail.com';
$password = 'Ks3IGJuW';

$create_contact_url = 'https://esputnik.com/api/v1/contact';
$contact = new stdClass();
$contact->channels = array(array('type' => 'email', 'value' => $email));

// adding contact to esputnik
send_request($create_contact_url, $contact, $user, $password);

function send_request($url, $json_value, $user, $password) {
    $ch = curl_init();
    
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_USERPWD, $user.':'.$password);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_SSLVERSION, 6);

    $output = curl_exec($ch);
    
	curl_close($ch);
	echo($output);
}

// sending letter
$letter = file_get_contents('../letter/dist/index.html');

$mail = new PHPMailer;
$mail->CharSet = 'utf-8';

$mail->SMTPDebug = 3;                                 // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.adm.tools';  					  // Specify smtp server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'valeria@bikosignals.com';          // mail login
$mail->Password = '3Am8C9T3Jzoh'; 				      // mail password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$mail->setFrom('valeria@bikosignals.com');
$mail->addAddress($email);
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Risk Manager video';
$mail->Body    = $letter;

if(!$mail->send()) {
    header('Location: https://bikosignals.com/index.html');
	exit;
} else {
	header('Location: https://bikosignals.com/index.html');
	exit;
}
?>