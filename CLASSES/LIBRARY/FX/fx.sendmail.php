<?php
	
include_once "fx.php";
include_once "class.phpmailer.php";
	
class SendEmailFX extends FX{
	
	//local class variables
	
	public function SendEmailFX(){
		//empty constructor
	} 
	
	public function sendEmail($to, $cc, $from, $subject, $msg){

		$mail = new PHPMailer(); // defaults to using php "mail()"

		$mail->IsSMTP(); // telling the class to use SMTP

		$mail->Host       = "smtp.gmail.com"; 			// SMTP server
		$mail->SMTPDebug  = 0;                     		// enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  		// enable SMTP authentication
		$mail->SMTPSecure = "tls"; 
		$mail->Port       = 587;                    		// set the SMTP port for the GMAIL server
		$mail->Username   = "user@email.com"; 	// SMTP account username
		$mail->Password   = "password";        				// SMTP account password
	

		$mail->From = $from;
		$mail->FromName = "From Name";
		
		$mail->AddAddress($to);
		if($cc != "") $mail->AddCC($cc);
		
		$mail->Subject = $subject;
		$mail->Body = $msg;
		$mail->MsgHTML($this->htmlEmail($msg));
		
		$sent = $mail->Send();
		
	}
	
	private function htmlEmail($msg){
		return $msg;
	}
	
}	
	
?>