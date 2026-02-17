<?php

/**
 * Example 6: Error Handling
 *
 * This example demonstrates proper error handling when working with the UniFi API,
 * including handling different types of exceptions and HTTP errors.
 */

require_once __DIR__.'/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;

$config = require_once __DIR__.'/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey = $config['api_key'];
$siteId = $config['site_id'];
$verifySsl = $config['verify_ssl'];

echo "UniFi API Client - Error Handling Example\n";
echo str_repeat('=', 50)."\n\n";

// 1. Basic error handling
echo "1. Basic Error Handling\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId($siteId);

    // Attempt to get a device with an invalid ID
    echo "   Attempting to get device with invalid ID...\n";
    $response = $apiClient->devices()->get('invalid-uuid-12345');
    $device = $response->json();

    echo "   Success! Device found.\n";
} catch (ClientException $e) {
    // 4xx errors (client errors)
    echo '   Client Error: '.$e->getMessage()."\n";
    echo '   HTTP Status: '.$e->getResponse()->status()."\n";
    echo "   This usually means the requested resource was not found or the request was malformed.\n";
} catch (ServerException $e) {
    // 5xx errors (server errors)
    echo '   Server Error: '.$e->getMessage()."\n";
    echo '   HTTP Status: '.$e->getResponse()->status()."\n";
    echo "   This indicates an error on the UniFi Controller side.\n";
} catch (RequestException $e) {
    // General request errors (network issues, timeouts, etc.)
    echo '   Request Error: '.$e->getMessage()."\n";
    echo "   This could be a network connectivity issue or timeout.\n";
}
echo "\n";

// 2. Handling invalid site ID
echo "2. Handling Invalid Site ID\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId('00000000-0000-0000-0000-000000000000');  // Invalid site ID

    echo "   Attempting to list devices for invalid site...\n";
    $response = $apiClient->devices()->listAdopted();
    $devices = $response->json();

    echo '   Success! Found '.count($devices['data'] ?? [])." devices.\n";
} catch (ClientException $e) {
    echo "   Client Error: The site ID is invalid or you don't have access to it.\n";
    echo '   HTTP Status: '.$e->getResponse()->status()."\n";
} catch (RequestException $e) {
    echo '   Request Error: '.$e->getMessage()."\n";
}
echo "\n";

// 3. Handling missing site ID
echo "3. Handling Missing Site ID\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    // Note: We're not setting a site ID here

    echo "   Attempting to list devices without setting site ID...\n";
    $response = $apiClient->devices()->listAdopted();
    $devices = $response->json();

    echo '   Success! Found '.count($devices['data'] ?? [])." devices.\n";
} catch (\RuntimeException $e) {
    // This is thrown by our requireSiteId() method
    echo '   Runtime Error: '.$e->getMessage()."\n";
    echo "   Solution: Call \$apiClient->setSiteId() before using site-specific endpoints.\n";
} catch (RequestException $e) {
    echo '   Request Error: '.$e->getMessage()."\n";
}
echo "\n";

// 4. Handling authentication errors
echo "4. Handling Authentication Errors\n";
try {
    $badClient = new UnifiClient($controllerUrl, 'invalid-api-key-12345', $verifySsl);
    $badClient->setSiteId($siteId);

    echo "   Attempting to connect with invalid API key...\n";
    $response = $badClient->sites()->list();
    $sites = $response->json();

    echo '   Success! Found '.count($sites['data'] ?? [])." sites.\n";
} catch (ClientException $e) {
    $status = $e->getResponse()->status();
    if ($status === 401 || $status === 403) {
        echo "   Authentication Error: Invalid API key or insufficient permissions.\n";
        echo "   HTTP Status: {$status}\n";
        echo "   Solution: Check your API key and ensure it has the necessary permissions.\n";
    } else {
        echo '   Client Error: '.$e->getMessage()."\n";
    }
} catch (RequestException $e) {
    echo '   Request Error: '.$e->getMessage()."\n";
}
echo "\n";

// 5. Graceful error handling with fallback
echo "5. Graceful Error Handling with Fallback\n";
function getDevicesSafely(UnifiClient $apiClient): array
{
    try {
        $response = $apiClient->devices()->listAdopted();

        return $response->json()['data'] ?? [];
    } catch (ClientException $e) {
        error_log('Client error getting devices: '.$e->getMessage());

        return [];
    } catch (ServerException $e) {
        error_log('Server error getting devices: '.$e->getMessage());

        return [];
    } catch (RequestException $e) {
        error_log('Request error getting devices: '.$e->getMessage());

        return [];
    }
}

$apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
$apiClient->setSiteId($siteId);

$devices = getDevicesSafely($apiClient);
echo '   Retrieved '.count($devices)." devices (with error handling)\n\n";

// 6. Checking response status before processing
echo "6. Checking Response Status\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId($siteId);

    $response = $apiClient->devices()->listAdopted();

    if ($response->successful()) {
        $data = $response->json();
        echo '   Success! Status: '.$response->status()."\n";
        echo '   Retrieved '.count($data['data'] ?? [])." devices\n";
    } else {
        echo '   Request failed with status: '.$response->status()."\n";
        echo '   Response body: '.$response->body()."\n";
    }
} catch (RequestException $e) {
    echo '   Request failed: '.$e->getMessage()."\n";
}
echo "\n";

echo str_repeat('=', 50)."\n";
echo "Error handling examples completed!\n\n";

echo "Summary of Exception Types:\n";
echo "- RuntimeException: Thrown when required parameters are missing (e.g., site ID)\n";
echo "- ClientException: 4xx HTTP errors (not found, bad request, unauthorized, etc.)\n";
echo "- ServerException: 5xx HTTP errors (server errors on UniFi Controller)\n";
echo "- RequestException: General request failures (network issues, timeouts, etc.)\n";
