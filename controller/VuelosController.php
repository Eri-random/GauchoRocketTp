<?php

class VuelosController {
    private $printer;
    private $vuelosModel;
   
    

    public function __construct($printer, $vuelosModel) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;

    }

    public function buscarVuelos (){
        if (isset($_GET["origen"]) ) {
            $origen = $_GET["origen"];
        }else {
            $origen = "";
        }if( isset($_GET["destino"]) ){
            $destino = $_GET["destino"];
        }else{
            $destino = "";
        } if( isset($_GET["fecha"]) ){
            $fecha = $_GET["fecha"];
           
        }else{
            $fecha = "";
            
        }

    
        if(!isset($_SESSION["logueado"]) || (isset($_SESSION["logueado"]) && !$_SESSION["logueado"] )){
            
        if($origen == "" && $destino == "" && $fecha == ""){
            
            $data["esNada"] = "esNada";
            $data["vacio"] = true;//Esta vacio Cartelito
            $data["viajes"] = $this->vuelosModel->getVuelos();
            $data["fechas"] = $this->vuelosModel->getFechas();
            $data["origenes"] = $this->vuelosModel->getOrigen();
            $data["destinos"] = $this->vuelosModel->getDestino();


               
                
            echo $this->printer->render("HomeView.html", $data);
        }else{

            $data["origen_buscado"] = $origen;
            $data["destino_buscado"] = $destino;
            $data["fecha_buscada"] = $fecha;


            $data["esNada"] = "esNada";


            $data["fechas"] = $this->vuelosModel->getFechas();
            $data["origenes"] = $this->vuelosModel->getOrigen();
            $data["destinos"] = $this->vuelosModel->getDestino();
            $data["recorrido"] = $this->vuelosModel->getRecorrido();
            $data["viajes"] = $this->vuelosModel->buscarVuelos($origen,$destino,$fecha);
            if(empty($data["viajes"]) ){
                $data["error"] = true;//No se encontro resultado cartelito
            }else{
                $data["error"] = false;
            }
            
            echo $this->printer->render("HomeView.html", $data);
        }

    } else if (isset($_SESSION["esAdmin"])) {
        $data["esAdmin"] = $_SESSION["esAdmin"];
        if($origen == "" && $destino == "" && $fecha == ""){
            $data["vacio"] = true;//Esta vacio Cartelito    
            $data["viajes"] = $this->vuelosModel->getVuelos();
            $data["fechas"] = $this->vuelosModel->getFechas();
            $data["origenes"] = $this->vuelosModel->getOrigen();
            $data["destinos"] = $this->vuelosModel->getDestino();
            $data["recorrido"] = $this->vuelosModel->getRecorrido();
            echo $this->printer->render("HomeView.html", $data);
        }else{
            
            $data["origen_buscado"] = $origen;
            $data["destino_buscado"] = $destino;
            $data["fecha_buscada"] = $fecha;

            $data["fechas"] = $this->vuelosModel->getFechas();
            $data["origenes"] = $this->vuelosModel->getOrigen();
            $data["destinos"] = $this->vuelosModel->getDestino();
            $data["recorrido"] = $this->vuelosModel->getRecorrido();
            $data["viajes"] = $this->vuelosModel->buscarVuelos($origen,$destino,$fecha);
            if(empty($data["viajes"]) ){
                $data["error"] = true;//No se encontro resultado cartelito
            }else{
                $data["error"] = false;
            }
           
            echo $this->printer->render("HomeView.html", $data);
        }
    }else if (isset($_SESSION["esClient"])){
        $data["esClient"] = $_SESSION["esClient"];
        if($origen == "" && $destino == "" && $fecha == ""){
            $data["vacio"] = true;//Esta vacio Cartelito    
            $data["viajes"] = $this->vuelosModel->getVuelos();
            $data["fechas"] = $this->vuelosModel->getFechas();
            $data["origenes"] = $this->vuelosModel->getOrigen();
            $data["destinos"] = $this->vuelosModel->getDestino();
            $data["recorrido"] = $this->vuelosModel->getRecorrido();
            echo $this->printer->render("HomeView.html", $data);
        }else{
            $data["origen_buscado"] = $origen;
            $data["destino_buscado"] = $destino;
            $data["fecha_buscada"] = $fecha;

            $data["fechas"] = $this->vuelosModel->getFechas();
            $data["origenes"] = $this->vuelosModel->getOrigen();
            $data["destinos"] = $this->vuelosModel->getDestino();
            $data["recorrido"] = $this->vuelosModel->getRecorrido();
            $data["viajes"] = $this->vuelosModel->buscarVuelos($origen,$destino,$fecha);
            if(empty($data["viajes"]) ){
                $data["error"] = true;//No se encontro resultado cartelito
            }else{
                $data["error"] = false;
            }
            
            echo $this->printer->render("HomeView.html", $data);
    }
    }
}
}


