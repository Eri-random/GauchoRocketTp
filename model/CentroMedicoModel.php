<?php


class CentroMedicoModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getCentroMedico(){
        $sql = "SELECT * FROM centro_medico";
        $result = $this->database->query($sql);
        return $result;
        
    }
    public function resultadoChequeo(){

        $result = rand(1,3);
        return $result;

    }

    public function insertChequeo($id_centro, $id_usuario){
        $result = $this->resultadoChequeo();
        $sql = "INSERT INTO chequeo (id_centro_medico, codigo, id_usuario) VALUES ('$id_centro', '$result', '$id_usuario')";
        $query = $this->database->query($sql);
        return $result;

        
    }

    
    public function getChequeo($id_centro, $id_user){
        $sql = "SELECT * FROM chequeo WHERE id_centro_medico = '$id_centro' and id_usuario = $id_user";
        $result = $this->database->query($sql);
        return $result;
    }

    public function getChequeoById($id_user){
        $sql = "SELECT codigo FROM chequeo WHERE id_usuario = '$id_user'";
        $result = $this->database->query($sql);
        if($result != null){
            return $result;
        } else{
            return null;
        }
     
    }


        
    





    }











