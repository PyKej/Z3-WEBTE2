<?php
session_start();
// Set a session expiration timestamp if not already set
if (!isset($_SESSION['expiration'])) {
    // $_SESSION['expiration'] = strtotime('tomorrow');
    $_SESSION['expiration'] = time() + 20;
}

// Check if the session has expired
if (time() > $_SESSION['expiration']) {
    // Session has expired, so clear and destroy it
    // delete file cookie if exists
    $cookieFile = __DIR__ . DIRECTORY_SEPARATOR . '../curl/cookie.txt';
    if (file_exists($cookieFile)) {
        unlink($cookieFile);
    }

    session_unset();
    session_destroy();
}
?>