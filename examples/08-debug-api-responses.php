<?php
/**
 * Debug Script - API Response Inspector
 *
 * This script helps debug API responses by showing the raw data
 * and HTTP status codes.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$config = require_once __DIR__ . '/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$verifySsl     = $config['verify_ssl'];

echo "UniFi API Debug Script\n";
echo str_repeat('=', 70) . "\n\n";

// Initialize the API client
$apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);

// Test 1: Application Info
echo "TEST 1: Application Info\n";
echo str_repeat('-', 70) . "\n";
try {
    $response = $apiClient->applicationInfo()->get();

    echo "HTTP Status: " . $response->status() . "\n";
    echo "Successful: " . ($response->successful() ? 'Yes' : 'No') . "\n";
    echo "Headers:\n";
    foreach ($response->headers() as $key => $values) {
        echo "  {$key}: " . implode(', ', $values) . "\n";
    }
    echo "\nRaw Response Body:\n";
    echo $response->body() . "\n";

    echo "\nParsed JSON:\n";
    print_r($response->json());

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
    if (method_exists($e, 'getResponse')) {
        echo "Response Status: " . $e->getResponse()->status() . "\n";
        echo "Response Body: " . $e->getResponse()->body() . "\n";
    }
}

echo "\n" . str_repeat('=', 70) . "\n\n";

// Test 2: List Sites
echo "TEST 2: List Sites\n";
echo str_repeat('-', 70) . "\n";
try {
    $response = $apiClient->sites()->list();

    echo "HTTP Status: " . $response->status() . "\n";
    echo "Successful: " . ($response->successful() ? 'Yes' : 'No') . "\n";
    echo "Headers:\n";
    foreach ($response->headers() as $key => $values) {
        echo "  {$key}: " . implode(', ', $values) . "\n";
    }
    echo "\nRaw Response Body:\n";
    echo $response->body() . "\n";

    echo "\nParsed JSON:\n";
    print_r($response->json());

    echo "\nChecking for 'data' key: " . (isset($response->json()['data']) ? 'EXISTS' : 'MISSING') . "\n";

    $json = $response->json();
    if (is_array($json)) {
        echo "Top-level keys in response: " . implode(', ', array_keys($json)) . "\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
    if (method_exists($e, 'getResponse')) {
        echo "Response Status: " . $e->getResponse()->status() . "\n";
        echo "Response Body: " . $e->getResponse()->body() . "\n";
    }
}

echo "\n" . str_repeat('=', 70) . "\n\n";

// Test 3: Raw cURL test (to verify connectivity)
echo "TEST 3: Raw cURL Test\n";
echo str_repeat('-', 70) . "\n";
$ch = curl_init($controllerUrl . '/proxy/network/integration/v1/info');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => $verifySsl,
    CURLOPT_SSL_VERIFYHOST => $verifySsl ? 2 : 0,
    CURLOPT_HTTPHEADER => [
        'X-API-KEY: ' . $apiKey,
        'Accept: application/json',
    ],
]);

$curlResponse = curl_exec($ch);
$curlInfo = curl_getinfo($ch);
$curlError = curl_error($ch);
curl_close($ch);

echo "cURL HTTP Code: " . $curlInfo['http_code'] . "\n";
echo "cURL Error: " . ($curlError ?: 'None') . "\n";
echo "Response:\n" . $curlResponse . "\n";

echo "\n" . str_repeat('=', 70) . "\n";
echo "Debug complete!\n";
