<?php
/**
 * Kwara State Polytechnic - School Fees System (PHP)
 * Database Configuration
 */

// Prevent any accidental HTML output from PHP errors
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(E_ALL);

define('DB_HOST', 'localhost');
define('DB_NAME', 'kwarapoly_fees');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // Return clean JSON error — do NOT output anything else
            http_response_code(503);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Database connection failed. Please check your config.php settings.'
            ]);
            exit;
        }
    }
    return $pdo;
}