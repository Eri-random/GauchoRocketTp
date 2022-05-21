<?php
include_once("exceptions/EntityFoundException.php");

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require 'vendor/autoload.php';

class UsuarioModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function logUser($data) {
        $email = $data["email"];
        $password = md5($data["password"]);

        $userFound = $this->getUser($email, $password)[0];

        if (sizeof($userFound) === 0) {
            throw new EntityNotFoundException("El usuario no existe");
        }

        $_SESSION["rol"] = $userFound["id_rol"];

        return $userFound;
    }

    public function agregarUsuario($data) {
        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $direccion = $data["direccion"];
        $email = $data["email"];
        $encryptedPassword = md5($data["password"]);
        $id_rol = 2;

        $this->checkUserNotExists($email);

        $sql = "INSERT INTO gaucho_rocket.usuario (nombre, apellido, direccion,email, password, id_rol) 
                VALUES ('$nombre', '$apellido', '$direccion', '$email', '$encryptedPassword', '$id_rol')";

        $this->database->query($sql);
    }


    public function getUserByEmail($email) {
        return $this->database->query("SELECT * FROM gaucho_rocket.usuario where email = '$email'");
    }

    public function getUser($email, $password) {
        return $this->database->query("SELECT * FROM usuario where email = '$email' AND password = '$password'");
    }

    public function getUsuarios() {
        return $this->database->query("SELECT * FROM usuario where rol = 'user' and activo = true");
    }

    private function checkUserNotExists($email) {
        $userFound = $this->getUserByEmail($email);

        if (sizeof($userFound) > 0) {
            throw new EntityFoundException("El usuario ya existe");
        }
    }

    public function enviarEmail($email){

        
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer();

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'guachorocket2022@gmail.com';                     //SMTP username
        $mail->Password   = 'copahgytcmfvmzwv';                               //SMTP password
        $mail->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('guachorocket2022@gmail.com', 'gauchoRocket');
        $mail->addAddress('' . $email . '');     //Add a recipient          

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Bienvenido a Gaucho Rocket';
        $mail->Body    = 'Por favor ingrese al siguiente link para poder verificar su cuenta <a href="http://localhost/GauchoRocketTp/index.php?controller=usuario&method=activar&email=' . $email . '">http://localhost/GauchoRocketTp/index.php?controller=usuario&method=activar&email=' . $email . '</a>';
        return $mail->send();
    }

    public function activarUsuario($email){
        $this->database->query('UPDATE usuario SET activo = 1 WHERE email = "'.$email.'"');
    }


}
