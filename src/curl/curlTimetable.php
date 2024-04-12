<?php
    require_once('f_curl.php');

    // Your login details
    $loginUrl = 'https://is.stuba.sk/auth/'; // Login URL
    $targetUrl = 'https://is.stuba.sk/auth/katalog/rozvrhy_view.pl?rozvrh_student_obec=1;rozvrh_student=115349;zobraz=1;konani_od=25.03.2024;konani_do=31.03.2024;lang=sk'; // Target page URL after login
    $cookieFile = __DIR__ . '/cookie.txt';

    performLogin($targetUrl, $cookieFile);

    $response = accessPageWithCookies($targetUrl, $cookieFile);
    // echo $response;
        if($response === false) {
            echo 'Curl Error'. "<br>";
        } else {
            include 'saveToDb.php';
        }

?>