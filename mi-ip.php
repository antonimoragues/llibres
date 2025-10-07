<?php
// ip_publica.php
$ch = curl_init('https://checkip.amazonaws.com/');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 3,
    CURLOPT_USERAGENT      => 'php-curl'
]);
$ip = curl_exec($ch);

if ($ip === false) {
    http_response_code(502);
    echo "Error cURL: " . curl_error($ch) . PHP_EOL;
    curl_close($ch);
    exit(1);
}

curl_close($ch);
echo trim($ip) . PHP_EOL;
