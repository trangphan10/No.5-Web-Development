<?php 

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;

    public function connect(){
        $conn = new mysqli($this->host, $this->user, $this->password,$this->database);
        if ($conn->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }
        return $conn;
    }
}
?>