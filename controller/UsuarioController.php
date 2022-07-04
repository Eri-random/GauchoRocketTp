<?php

class UsuarioController {
    private $printer;
    private $usuarioModel;
    private $userValidator;
    private $vuelosModel;
    private $centroMedicoModel;

    public function __construct($usuarioModel, $printer, $userValidator, $vuelosModel, $centroMedicoModel) {
        $this->printer = $printer;
        $this->usuarioModel = $usuarioModel;
        $this->userValidator = $userValidator;
        $this->vuelosModel = $vuelosModel;
        $this->centroMedicoModel = $centroMedicoModel;
    }

    public function show() {
        if (isset($_SESSION["esClient"]) || isset($_SESSION["esAdmin"])) {
            Navigation::redirectTo("/home");
        }

        echo $this->printer->render("registroView.html");
    }

    public function login() {
        try {
            // validaciones
            if (isset($_SESSION["esClient"]) || isset($_SESSION["esAdmin"])) {
                Navigation::redirectTo("/home");
            }

            $this->userValidator->validateUserToLogin($_POST);

            // ejecucion
            $user = $this->usuarioModel->logUser($_POST);

            // presentacion
            $data["nombre"] = $user["nombre"];

            $_SESSION["rol"] = $user["id_rol"];

            if(isset($_SESSION["rol"]) && $_SESSION["rol"]==2){
                $_SESSION["esClient"] = true;
                $_SESSION["email"] = $user["email"];
                $_SESSION["id"] = $user["id"];
                $_SESSION["nombre"] = $user["nombre"];
                $data["esClient"] = true; 
                $data["lugares"] = $this->vuelosModel->getLugares();
                $data["chequeo"] = $this->centroMedicoModel->getChequeoById($_SESSION["id"]);
                echo $this->printer->render("homeView.html", $data);
                exit();
            }
            
            if(isset($_SESSION["rol"]) && $_SESSION["rol"]==1){
                $_SESSION["esAdmin"] = true;
                $data["esAdmin"] = true;
                $_SESSION["email"] = $user["email"];
                $_SESSION["id"] = $user["id"];
                $_SESSION["nombre"] = $user["nombre"];
                
                $data["viajes"] = $this->vuelosModel->getVuelos();
                $data["lugares"] = $this->vuelosModel->getLugares();
                echo $this->printer->render("homeView.html", $data);
            }
        

        } catch (ValidationException|EntityNotFoundException $exception) {
            $data["error"] = "Usted no esta registrado";

            echo $this->printer->render("registroView.html", $data);

        }
    }

    public function logout() {
        session_destroy();
        Navigation::redirectTo("/home");
    }

    public function procesarRegistro() {
        try {
            // validaciones
            if (isset($_SESSION["esClient"]) || isset($_SESSION["esAdmin"])) {
                Navigation::redirectTo("/home");
            }

            $this->userValidator->validateUserToRegister($_POST);

            // ejecucion
            $this->usuarioModel->agregarUsuario($_POST);

            $this->usuarioModel->enviarEmail($_POST["email"]);

            // presentacion
            $data["mensaje"] = "Ya puedes validar tu cuenta a traves de email";

            echo $this->printer->render("HomeView.html",$data);
        } catch (ValidationException|EntityFoundException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("registroView.html", $data);
        }
    }

    public function activar() {
        $email = $_GET["email"];
        $this->usuarioModel->activarUsuario($email);
        $data["mensaje"]="Su cuenta ha sido validada correctamente";

        echo $this->printer->render("HomeView.html",$data);
    }


}
