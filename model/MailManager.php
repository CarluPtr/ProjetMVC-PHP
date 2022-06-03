<?php

    //Load Composer's autoloader
    require 'vendor/autoload.php';
            //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    //Create an instance; passing `true` enables exceptions
    

class MailManager extends PHPMailer
{


    function sendemail($firstname, $birthname, $email, $subject, $message) {

        $mail = new PHPMailer(true);
        $name = $firstname . ' ' . $birthname;
        $body = "Bonjour" . ' ' . $name . '<hr>' . "Merci pour votre message : " . $message;

        // Outlook email infos

        $from = "youremail@outlook.fr";
        $password = "yourpassword";

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $from;                     //SMTP username
            $mail->Password   = $password;                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;        
            

            //Recipients
            $mail->setFrom($from, $name);
            $mail->addAddress($email);               //Name is optional

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
        echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}