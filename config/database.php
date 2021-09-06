<?php

class Database {

    private $dbname;
    private $hostname;
    private $username;
    private $password;
    private $connection;

    public function connect()
    {
        $this->hostname = "localhost";
        $this->dbname = "restapidb";
        $this->username = "root";
        $this->password = "";

        $this->connection = new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
        if($this->connection->connect_errno){
            print_r($this->connection->connect_error);
            exit;
        }else{
            return $this->connection;
        }
    }


}

