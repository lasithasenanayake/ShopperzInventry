<?php
    class MySqlConnector{

            private $settings;
            private $connection;

            function __construct($servername, $username, $password, $dbName){
                $this->settings = new stdClass();
                $this->settings->servername = $servername;
                $this->settings->username = $username;
                $this->settings->password = $password;
                $this->settings->dbName = $dbName;
            }

            public function Connect(){
                $settings = $this->settings;
                $conn = new mysqli($settings->servername, $settings->username, $settings->password);
                $conn->select_db($settings->dbName);

                if ($conn->connect_error) {
                    return $conn->connect_error;
                }else {
                    $this->connection = $conn;
                    $this->Query("CREATE DATABASE IF NOT EXISTS ". $settings->dbName);
                }
                return TRUE;
            }

            public function Disconnect(){
                if (isset($this->connection))
                    mysqli_close($this->connection);
            }

            public function Query($query){
                $result = mysqli_query($this->connection,$query);
               
                if ($result){
                    
                    return $result;
                }
                else 
                    return mysqli_error($this->connection);
            }

    }

?>