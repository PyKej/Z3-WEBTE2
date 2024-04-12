<?php
// Function to perform a login
function performLogin($loginUrl, $cookieFile) {
    if (!isset($_SESSION['login'])) {
        $_SESSION['login'] = 'logged';
    }

    $curl = curl_init();
    $postData = http_build_query([
        'credential_0' => 'xpitkakester', // Update with your actual username
        'credential_1' => 'Heeslo.12$45', // Update with your actual password
        'login_hidden' => '1',
        'destination' => '/auth/',
        'auth_id_hidden' => '0',
        'auth_2fa_type' => 'no',
    ]);

    curl_setopt($curl, CURLOPT_URL, $loginUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Note: Only for testing purposes

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// Function to access the desired page using cookies
function accessPageWithCookies($url, $cookieFile) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($curl);
    curl_close($curl);
    // echo $response;
    return $response;
}
?>