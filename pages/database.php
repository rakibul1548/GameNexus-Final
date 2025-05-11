<?php
// config/database.php

// Verify this file is being accessed correctly
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'game_store_user');
    define('DB_PASS', 'secure_password');
    define('DB_NAME', 'game_store');
}

function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_errno) {
                throw new Exception("MySQL connection failed: " . $conn->connect_error);
            }
            
            $conn->set_charset("utf8mb4");
            
            // Test the connection
            if (!$conn->ping()) {
                throw new Exception("Connection is not active");
            }
            
        } catch (Exception $e) {
            error_log("DB Error: " . $e->getMessage());
            die("Service unavailable. Please try again later.");
        }
    }
    
    return $conn;
}

// Usage in other files:
// $db = getDBConnection();
?>