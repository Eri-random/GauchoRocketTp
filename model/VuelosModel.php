<?php

class VuelosModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getVuelos() {

        $sql = "SELECT v.id, v.capacidad,v.fecha_partida, v.precio, v.duracion, e.nombre as equipo, r.nombre as circuito   FROM
        vuelo v join tipo_equipo e on v.id_equipo = e.id
                join recorrido r on v.id_recorrido = r.id
                where  r.id = v.id_recorrido"; 
                
                $result = $this->database->query($sql);
                return $result;  
    }
    public function getTipo(){

        $sql = "SELECT  r.nombre as circuito  FROM
        vuelo v join tipo_equipo e on v.id_equipo = e.id
                join recorrido r on v.id_recorrido = r.id
                where  r.id = v.id_recorrido"; 
                
                $result = $this->database->query($sql);
                return $result;  
    }

    public function buscarVuelos($origen,$destino,$fecha) {
        $sql = "SELECT  distinct v.id, v.capacidad, v.fecha_partida, v.precio, e.nombre as equipo, r.nombre as circuito from 
        vuelo v join tipo_equipo e on v.id_equipo = e.id
                join recorrido r on v.id_recorrido = r.id
                join destino d on d.id_recorrido =  r.id where d.nombre in ('$origen', '$destino') and v.fecha_partida = '$fecha' and v.id_recorrido = r.id 
                group by v.id having count(d.nombre) = 2"; 

        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getFechas(){
        $sql = "SELECT  DISTINCT fecha_partida as fecha FROM vuelo";
        $resultado = $this->database->query($sql);
        return $resultado;
    }
    public function getOrigen(){
        $sql = "SELECT distinct d.nombre as origen from destino d join recorrido r on d.id_recorrido = r.id";
        $resultado = $this->database->query($sql);
        return $resultado;
    }
    public function getDestino(){
        $sql = "SELECT distinct d.nombre as destino from destino d join recorrido r on d.id_recorrido = r.id";
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getRecorrido(){
        $sql = "SELECT distinct d.nombre as destino
        from destino d join recorrido r on d.id_recorrido = r.id 
        join vuelo v on r.id = v.id_recorrido where v.id_recorrido = d.id_recorrido";
        
        $resultado = $this->database->query($sql);
        return $resultado;

       
    }

}

