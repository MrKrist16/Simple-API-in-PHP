<?php
    class Database {
        private $host = 'localhost';
        private $db_name = 'api_db';
        private $username = 'root';
        private $password = 'root';
        public $connection;

        public function getConnection(){
            $this->connection = null;
            try {
                $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->connection->exec('set name utf8');
            }catch(PDOException $exception){
                echo 'Connection error: ' . $exception->getMessage();
            }

            return $this->connection;
        }
    }
?>