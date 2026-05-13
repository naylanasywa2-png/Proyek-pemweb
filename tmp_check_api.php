<?php
$key = 'Rya110279c92739d31bf39fbjC4hTcJI';
$url = 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/calculate?'.http_build_query([
    'shipper_destination_id' => 12043,
    'receiver_destination_id' => 4982,
    'weight' => 800,
    'item_value' => 50000,
    'cod' => 'no',
]);
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'x-api-key: ' . $key,
        'Accept: application/json',
    ],
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
]);
$response = curl_exec($curl);
$error = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
if ($error) {
    echo "ERROR: $error\n";
    exit(1);
}

echo "HTTP: $httpCode\n";
echo $response . "\n";
