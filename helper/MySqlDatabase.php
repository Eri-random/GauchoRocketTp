<?php

class MySqlDatabase {

    private $host;
    private $user;
    private $pass;
    private $database;
    private $port;

    private $conn;

    public function __construct($host, $user, $pass, $database,$port) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->port = $port;
        $this->connect();
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function query($sql) {
        $result = mysqli_query($this->conn, $sql);

        if ($this->isAnInsertQuery($result)) return;


        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    private function isAnInsertQuery($query) {
        return gettype($query) === "boolean";
    }


    private function connect() {
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database,$this->port);
        if (!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
        }
        $this->conn = $conn;
    }

    private function disconnect() {
        mysqli_close($this->conn);
    }
}