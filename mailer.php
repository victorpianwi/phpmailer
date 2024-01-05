<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$gmail_account = "youremail@mailprovider.com";
$gmail_password = "youremailpassword";
$sent_from_address = "emailname@domain.com";
$reply_to_address = "emailname@domain.com";
$sitename = "Domain Name";
$sitelink = "domainlink.com";
$sitelogo = "domainlink.com/yourlogo.png";

class mailer {
    public function send($to, $subject, $message) {
        global $gmail_account;
        global $gmail_password;
        global $sent_from_address;
        global $reply_to_address;
        global $sitename;
        if(empty($to) OR empty($subject) OR empty($message)) : return false; endif;

        $gmailSMTPEmail = $gmail_account;
        $gmailSMTPPassword = $gmail_password;
        $senderEmail = $sent_from_address;
        $replyEmail = $reply_to_address;
        $websiteName = $sitename;

        if(empty($gmailSMTPEmail) OR empty($gmailSMTPPassword) OR empty($senderEmail)
        OR empty($replyEmail) OR empty($websiteName)) : return false; endif;
        
        require 'mailer/autoload.php';

        $mail = new PHPMailer();

        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        // $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $gmailSMTPEmail;                     //SMTP username
        $mail->Password   = $gmailSMTPPassword;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($senderEmail, $websiteName);
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo($replyEmail, $websiteName);
        // $mail->addCC('');
        // $mail->addBCC('');
        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        if(!$mail->send()) return false; //echo 'Mailer Error: ' . $mail->ErrorInfo;
        else

        return true; //Message sent!
    }

    public function templage($mailsubject, $mailmessage, $to = null){
        if(empty($mailsubject) OR empty($mailmessage) OR empty($to)) : return ''; endif;

        global $sitename;
        global $sitelink;
        global $sitelogo;
        global $reply_to_address;

        $companyName = $sitename;
        $websiteLink = $sitelink;
        
        $mailsubject = str_replace('[__SITE_TITLE__]', $companyName, $mailsubject);

        $mailmessage = str_replace('[__SITE_TITLE__]', $companyName, $mailmessage);

        $mailsubject = str_replace('[__SITE_URL__]', $websiteLink, $mailsubject);
                
        $mailmessage = str_replace('[__SITE_URL__]', $websiteLink, $mailmessage);
        
        return "
        <html>
            <body>
                <div style=\"display: block; background: #f7f7f7\">
                    <div style=\"display: block; background: #fff; width:85%; margin: 0 auto;\">
                        <div style=\"display: block; background: #f7f7f7; text-align:center; padding: 20px; border-bottom:2px solid #7367F0\">
                            <img src=\"".$sitelink.$sitelogo."\" alt=\"" . $companyName . "\" style=\"max-height:60px; display: inline-block\" />
                        </div>
                        <div style=\"display: block; padding: 20px\">" . $mailmessage . "</div>
                        <div style=\"display: block; background: #7367F0; color:#fff; text-align:center; padding: 20px; margin-bottom:20px; font-size:11px\">(c) Copyright " . date("Y") . " | " . $companyName . "</div>
                    </div>
                </div>
            </body>
        </html>";
    }

}

$mailer = new mailer();

$subject = "Test Subject";

$message = "This is the test message";

if($mailer->send("testmail@gmail.com", $subject, $message)){
    echo "success";
} else {
    echo "an unknown error occured!";
}

?>
