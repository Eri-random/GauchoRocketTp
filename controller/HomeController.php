<?php

class HomeController {
    private $printer;
    private $vuelosModel;
    private $centroMedicoModel;

    public function __construct($printer, $vuelosModel,$centroMedicoModel ) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;
        $this->centroMedicoModel = $centroMedicoModel;

    }

    function show() {
        if (isset($_SESSION["esClient"])) {
            $data["esClient"] = true;
            $data["nombre"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
            $data["chequeo"] = $this->centroMedicoModel->getChequeoById($_SESSION["id"]);
        }

    else if(isset($_SESSION["esAdmin"])){
        $data["esAdmin"] = true;
        $data["nombre"] = $_SESSION["nombre"];
        $data["id"] = $_SESSION["id"];
        $data["viajes"] = $this->vuelosModel->getVuelos();
       
    }

    $data["lugares"] = $this->vuelosModel->getLugares();

    if(!isset($_SESSION["actualizado"]) || $_SESSION["actualizado"]==false){

        $_SESSION["actualizado"]=false;

        echo $this->printer->render("homeView.html", $data);
    }

    if($_SESSION["actualizado"]== true){
        $data["actualizado"] = $_SESSION["actualizado"];
        $data["idModificado"]=$_SESSION["idModificado"];

        $_SESSION["actualizado"]=false;
        
        echo $this->printer->render("homeView.html", $data);
    }

     }
}
    


