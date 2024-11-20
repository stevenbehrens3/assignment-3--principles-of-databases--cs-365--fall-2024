<?php
// Database configuration constants
define("DBHOST", "localhost");
define("DBNAME", "student_passwords");
define("DBUSER", "passwords_user");
define("DBPASS", "");

// Encryption parameters
define("ENCRYPTION_KEY", 'super_secret_password');
define("INIT_VECTOR", '0987654321098765');

try {
    // Initialize database connection
    $db = new PDO(
        "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4",
        DBUSER,
        DBPASS
    );

    // Set PDO attributes
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configure encryption parameters
    $db->exec("SET block_encryption_mode = 'aes-256-cbc'");
    $db->exec("SET @key_str = UNHEX(SHA2('" . ENCRYPTION_KEY . "', 256))");
    $db->exec("SET @init_vector = '" . INIT_VECTOR . "'");

} catch (PDOException $e) {
    // Display error and exit if database connection fails
    echo "<p>Error connecting to the database:</p>\n";
    echo "<p id='error'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
    exit;
}
?>
