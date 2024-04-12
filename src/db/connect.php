<?php
// ERRORS
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

include 'config.php';

function getDBConnection() {
    try {
        // Set the DSN (Data Source Name)
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";

        // Create a PDO instance
        $pdo = new PDO($dsn, DB_USER, DB_PASS);

        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Optional: Set default fetch mode to FETCH_ASSOC for associative arrays
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Return the PDO instance
        return $pdo;

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        // return 'pomocna chyba';
    }
}
?>