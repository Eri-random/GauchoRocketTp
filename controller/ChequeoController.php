<?php

class ChequeoController {
    private $printer;
    private $centroMedicoModel;
    
    

  

    public function __construct($printer, $centroMedicoModel) {
        $this->printer = $printer;
        $this->centroMedicoModel = $centroMedicoModel;

    }

    public function show() {
           
            if(isset($_SESSION["esClient"])){
                
                $data["esClient"] = $_SESSION["esClient"];
                $data["usuario"] = $_SESSION["nombre"];
                $data["centros"] = $this->centroMedicoModel->getCentroMedico();  

                echo $this->printer->render("centroMedicoView.html", $data);
                exit();
            } else{
                header("Location: /home");
            exit();

            }
        

       
    }

    public function chequeoMedico(){

        if(isset($_SESSION["esClient"])){
                
            $idCentro = (int)$_GET["idCentro"];

            $data["esClient"] = $_SESSION["esClient"];
            $data["usuario"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];

            $data["chequeo"] = $this->centroMedicoModel->insertChequeo($idCentro, $_SESSION["id"]);

            
      
            


            echo $this->printer->render("resultadoChequeoMedicoView.html", $data);
            exit();
        } else{
            header("Location: /home");
        exit();

        }


    }


    

}