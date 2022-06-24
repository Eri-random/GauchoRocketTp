<?php

class VuelosModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function agregarVuelo($capacidad,$fecha_partida,$hora,$lugar_partida, $destino, $precio, $id_tipo_equipo, $id_tipo_viaje, $id_tipo_cabina) {
        
        $sql =  "INSERT into vuelo (activo, capacidad, fecha_partida, hora, lugar_partida, destino, precio, id_tipo_equipo,id_tipo_viaje, id_tipo_cabina) values 
        ( true ,'$capacidad', '$fecha_partida', '$hora', '$lugar_partida', '$destino', '$precio', '$id_tipo_equipo', '$id_tipo_viaje', '$id_tipo_cabina')";
        $this->database->query($sql);

    }

    public function getVuelos() {

        return $this->database->query('SELECT v.id, v.capacidad, v.fecha_partida , v.hora, v.lugar_partida, v.destino, v.precio, te.nombre as equipo, v.id_tipo_viaje, v.id_tipo_cabina 
        from vuelo v join tipo_equipo te on v.id_tipo_equipo = te.id and v.activo = true');

    }

    public function getVuelosTest() {
        $results = $this->database->query("SELECT v.id, v.lugar_partida,v.destino,v.precio,v.id_tipo_equipo,v.id_tipo_viaje,tipo_equipo.nombre,recorrido.parada, DATE_FORMAT(fecha_partida, '%a. %d %M. %Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo as v JOIN tipo_equipo ON v.id_tipo_equipo = tipo_equipo.id LEFT JOIN recorrido ON v.id_tipo_equipo = recorrido.id_tipo_equipo AND v.id_tipo_viaje = recorrido.id_tipo_viaje AND v.activo = true");

        return $results;
    }

    public function buscarVuelos($origen,$destino,$fecha) {
        $sql = "SELECT  *,DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo join tipo_viaje on vuelo.id_tipo_viaje = tipo_viaje.id where lugar_partida = '$origen' and destino = '$destino' and fecha_partida = '$fecha' /*and activo = 'true'*/";
        
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getParadasCircuito1(){
        $sql = "SELECT  parada FROM recorrido as r  WHERE r.id_tipo_viaje = 2";

        $resultado = $this->database->query($sql);
        return $resultado;
    }

    
    public function getParadasCircuito2(){
        $sql = "SELECT  parada FROM recorrido as r  WHERE r.id_tipo_viaje = 3";
    
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getTransitoCircuito1BA(){
        $sql = "SELECT  transito FROM recorrido as r  WHERE r.id_tipo_viaje = 2 AND r.id_tipo_equipo = 2";

        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getTransitoCircuito1AA(){
        $sql = "SELECT  transito FROM recorrido as r  WHERE r.id_tipo_viaje = 2 AND r.id_tipo_equipo = 3";
        
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    
    public function getTransitoCircuito2BA(){
        $sql = "SELECT  transito FROM recorrido as r  WHERE r.id_tipo_viaje = 3 AND r.id_tipo_equipo = 2";
        
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getTransitoCircuito2AA(){
        $sql = "SELECT  transito FROM recorrido as r  WHERE r.id_tipo_viaje = 3 AND r.id_tipo_equipo = 3";
        
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getLugares(){
        $sql = "SELECT * FROM lugares";
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function updateCapacity($id, $quantity) {
        $sql = "UPDATE vuelo SET capacidad = capacidad - $quantity WHERE id = $id";

        $this->database->query($sql);
    }

    public function getVueloById($id) {
        $sql = "SELECT * FROM vuelo WHERE id = $id";


        return $this->database->query($sql);
    }

    public function deleteVuelo($id){
        $sql = "UPDATE vuelo  set activo = false WHERE id = $id";
        $this->database->query($sql);
    }

   

}