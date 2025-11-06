<?php
/**
 * Database Configuration
 * Webspace Innovation Hub Limited
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'u232647434_webspace';
    private $username = 'u232647434_web_user';
    private $password = 'byBD0suF#s5';
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            // Log error details
            error_log("Database Connection Error: " . $e->getMessage());
            error_log("Database: " . $this->db_name . " | Host: " . $this->host);
            
            // Production: Show generic message, log details
            if (ini_get('display_errors') == 0) {
                die("Database connection failed. Please contact the administrator.");
            } else {
                // Development: Show detailed error
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return $this->conn;
    }
}

