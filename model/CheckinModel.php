<?php
include_once("exceptions/EntityFoundException.php");
include_once("exceptions/EntityNotFoundException.php");


//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require 'vendor/autoload.php';

class CheckinModel {
    
    public function __construct() {
       
    }


    public function enviarEmailDeCheckin($email, $datos) {


        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer();
        $email = "gastons525@gmail.com";

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'guachorocket2022@gmail.com';                     //SMTP username
        $mail->Password = 'copahgytcmfvmzwv';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('guachorocket2022@gmail.com', 'gauchoRocket');
        $mail->addAddress('' . $email . '');     //Add a recipient          

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Check-in';
        $mail->Body = $datos;
        return $mail->send();
    }




}