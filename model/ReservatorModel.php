<?php

class ReservatorModel {
    private $database;
    private $vuelosModel;

    public function __construct($database, $vuelosModel) {
        $this->database = $database;
        $this->vuelosModel = $vuelosModel;
    }

    public function getRerservaByReserve($reserveId) {

        $sql = "SELECT r.id ,r.codigo, r.precio, r.confirmada, DATE_FORMAT(r.fecha,'%d-%m-%Y' ) as Fecha_reserva, DATE_FORMAT(v.fecha_partida, '%d-%m-%Y') as fecha_partida,v.hora as hora, c.nombre as cabina, s.nombre as servicio
        from reserva r join vuelo v on r.id_vuelo = v.id 
                        join tipo_cabina c on r.id_cabina = c.id
                        join tipo_servicio s on r.id_servicio = s.id where r.id = '" . $reserveId . "'";

        $resultado = $this->database->query($sql);
        return $resultado;
    }


    public function getReservesByUser($userId) {
        $sql = "SELECT * FROM reserva WHERE id_usuario = '" . $userId . "' AND confirmada = 0";

        return $this->database->query($sql);
    }

    public function getCabineTypes() {
        $sql = "SELECT * FROM tipo_cabina";

        return $this->database->query($sql);
    }

    public function getServiceTypes() {
        $sql = "SELECT * FROM tipo_servicio";

        return $this->database->query($sql);
    }

    public function confirmReserve($data, $idVuelo) {
        $this->checkVueloExists($idVuelo);

        $reservesMade = (int)$data["cantidad"];

        $this->checkVueloCapacity($idVuelo, $reservesMade);

        $this->vuelosModel->updateCapacity($idVuelo, $reservesMade);

        $getPriceQuery = "SELECT precio FROM vuelo WHERE id = '" . $idVuelo . "'";
        $vueloCost = $this->database->query($getPriceQuery)[0]["precio"];
        $data["vueloCost"] = $vueloCost;

        $reserveCost = $this->calculateReserveCost($data);

        for ($i = 1; $i <= $reservesMade; $i++) {
            $this->createReserve($data["cabina"], $data["servicio"], $idVuelo, $reserveCost);
        }

        return $reserveCost;
    }

    private function checkVueloExists($idVuelo) {
        $vuelo = $this->vuelosModel->getVueloById($idVuelo);

        if (!isset($vuelo) || sizeof($vuelo) == 0) {
            throw new ValidationException("El vuelo que quiere reservar no existe");
        }
        return true;

    }

    private function checkVueloCapacity($idVuelo, $reservesWanted) {
        $vuelo = $this->vuelosModel->getVueloById($idVuelo)[0];

        $capacity = $vuelo["capacidad"];
        $destino = $vuelo["destino"];


        if ($capacity == 0 || $reservesWanted > $capacity) {
            throw new ValidationException("El vuelo con destino a $destino no tiene mas lugares disponibles");
        }
    }

    private function calculateReserveCost($data) {
        $totalReserves = (int)$data["cantidad"];
        $cabineCost = $this->getCabineByName($data["cabina"])["recargo"];
        $serviceCost = $this->getServiceByName($data["servicio"])["recargo"];
        $vueloCost = (int)$data["vueloCost"];

        return $vueloCost + $totalReserves * ($cabineCost + $serviceCost);
    }

    private function createReserve($cabineName, $serviceName, $idVuelo, $price) {
        $code = uniqid();
        $cabineId = $this->getCabineByName($cabineName)["id"];
        $serviceId = $this->getServiceByName($serviceName)["id"];
        $userId = $_SESSION["id"];
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $fecha = date('Y-m-d H:i', time());
        $sql = "INSERT INTO reserva(codigo,precio,fecha,confirmada,id_vuelo,id_cabina,id_servicio,id_usuario,pagado) 
                VALUES ('$code',$price,'$fecha',0,$idVuelo,$cabineId,$serviceId,$userId,0)";

        $this->database->query($sql);
    }

    private function getServiceByName($name) {
        $sql = "SELECT * FROM tipo_servicio WHERE nombre = '$name'";

        return $this->database->query($sql)[0];
    }

    private function getCabineByName($name) {
        $sql = "SELECT * FROM tipo_cabina WHERE nombre = '$name'";

        return $this->database->query($sql)[0];
    }

    public function getCabinaMasVendida() {

        $sql = "SELECT tc.nombre as Cabina, count(r.id_cabina) as Cantidad from reserva r
        join  tipo_cabina tc on r.id_cabina = tc.id group by tc.nombre order by tc.nombre asc";

        return $this->database->query($sql);
    }

    public function getFacturacionMensual() {

        $sql = "SELECT monthname(STR_TO_DATE(fecha, '%Y-%m-%d')) as MES, sum(precio) as DINERO from reserva group by MONTH(STR_TO_DATE(fecha, '%Y-%m-%d')) order by MONTH(STR_TO_DATE(fecha, '%Y-%m-%d')) asc";

        return $this->database->query($sql);

    }

    public function getFacturacionByClient() {

        $sql = "SELECT u.apellido as apellido, u.nombre as nombre, sum(r.precio) as DINERO from reserva r
        join usuario u on r.id_usuario = u.id group by u.id order by u.nombre asc";
        return $this->database->query($sql);
    }

    public function getTasaDeOcupacionPorViaje() {

        $sql = "SELECT v.id as IdVuelo, v.capacidad as capacidad , count(r.id_usuario) as ocupacion from reserva r join vuelo v on
        r.id_vuelo = v.id group by v.id  order by v.id asc";
        return $this->database->query($sql);
    }


    public function updateReserva($idReserve) {
        $sql = "UPDATE reserva SET confirmada = 1 WHERE id = '" . $idReserve . "'";
        $this->database->query($sql);
        return true;

    }
    public function updatePagado($idReserve) {
        $sql = "UPDATE reserva SET pagado = 1 WHERE id = '" . $idReserve . "'";
        $this->database->query($sql);
        return true;

    }
    public function getPagoReserva($idReserve){
        $sql = "SELECT pagado from reserva where id = '" . $idReserve . "'";
          $resultado = $this->database->query($sql);
          $valore = $resultado[0]["pagado"];
          if($valore == "0"){
            return false;
          }else{
            return true;
          }
        
    }

    public function deleteReserva($idReserve) {
        $sql = "DELETE FROM reserva WHERE id = '" . $idReserve . "'";
        $this->database->query($sql);
        return true;
    }

    public function getExchangeRate($value, $exchangeType) {
        $exchangesRates = [
            'USD' => 0.1,
            'ARG' => 0.3
        ];

        return $value * $exchangesRates[$exchangeType];
    }


}