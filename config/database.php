<?php

    class Database {
        private $host = "127.0.0.1";
        private $db_name = "project_school";
        private $username = "root";
        private $password = "";
        private $conn;

        public function connection() {
            $this->conn = null;

            try {
                $this->conn = new PDO("mysql:host=". $this->host . ";dbname=" . $this->db_name,$this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Dadabase Error: ". $e->getMessage();
            }
            
            return $this->conn;
        }
    }
?>