<?php
class Database {
    public $host = "localhost";
    public $db_name = "u232955123_lex_juris";
    public $username = "u232955123_brandweave";
    public $password = "BrandWeave@25";
    public $conn;

    // public $host = "localhost";
    // public $db_name = "lex_juris";
    // public $username = "root";
    // public $password = "";
    // public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            // Check connection
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            // Set charset to utf8mb4
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

// Create database instance and connection
$database = new Database();
$conn = $database->getConnection();
?> 