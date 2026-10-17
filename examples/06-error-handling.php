<?php

/**
 * Example 6: Error Handling
 *
 * This example demonstrates proper error handling when working with the UniFi API.
 *
 * By default, the client returns failed (4xx/5xx) responses just like successful ones, so you
 * check them with $response->failed() or $response->successful(), or turn them into exceptions
 * with $response->throw(). Alternatively, enable throwOnErrors so every failed response throws.
 * Network problems and timeouts always throw a FatalRequestException.
 */

require_once __DIR__.'/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;

$config = require_once __DIR__.'/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey = $config['api_key'];
$siteId = $config['site_id'];
$verifySsl = $config['verify_ssl'];

echo "UniFi API Client - Error Handling Example\n";
echo str_repeat('=', 50)."\n\n";

// 1. Default behaviour: check the response
echo "1. Checking a Failed Response (default behaviour)\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId($siteId);

    echo "   Attempting to get a device that does not exist...\n";
    $response = $apiClient->devices()->get('00000000-0000-0000-0000-000000000000');

    if ($response->failed()) {
        // The body follows the API's error format: statusCode, statusName, code, message, requestId, ...
        echo '   HTTP Status: '.$response->status()."\n";
        echo '   Error code: '.($response->json('code') ?? 'n/a')."\n";
        echo '   Message: '.($response->json('message') ?? $response->body())."\n";
    } else {
        echo '   Device found: '.($response->json('name') ?? 'unnamed')."\n";
    }
} catch (FatalRequestException $e) {
    // Network issues, DNS failures, timeouts, SSL errors, ...
    echo '   Connection Error: '.$e->getMessage()."\n";
}
echo "\n";

// 2. Turning a failed response into an exception with throw()
echo "2. Using \$response->throw()\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId('00000000-0000-0000-0000-000000000000');  // A site that does not exist

    echo "   Attempting to list devices for a site that does not exist...\n";
    $devices = $apiClient->devices()->listAdopted()->throw()->json('data');

    echo '   Found '.count($devices ?? [])." devices.\n";
} catch (ClientException $e) {
    // ClientException covers all 4xx responses; specific subclasses exist (NotFoundException, ...)
    echo '   Client Error ('.get_class($e)."):\n";
    echo '   HTTP Status: '.$e->getResponse()->status()."\n";
    echo '   Error code: '.($e->getResponse()->json('code') ?? 'n/a')."\n";
} catch (ServerException $e) {
    echo '   Server Error: HTTP '.$e->getResponse()->status()."\n";
} catch (FatalRequestException $e) {
    echo '   Connection Error: '.$e->getMessage()."\n";
}
echo "\n";

// 3. Opt-in: throw on every failed response
echo "3. Throwing on Errors (opt-in)\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl, throwOnErrors: true);
    $apiClient->setSiteId($siteId);
    // or on an existing client: $apiClient->throwOnErrors();

    echo "   Attempting to get a device that does not exist...\n";
    $device = $apiClient->devices()->get('00000000-0000-0000-0000-000000000000')->json();

    echo '   Device found: '.($device['name'] ?? 'unnamed')."\n";
} catch (NotFoundException $e) {
    echo "   Not found (NotFoundException)\n";
    echo '   Message: '.($e->getResponse()->json('message') ?? 'n/a')."\n";
} catch (RequestException $e) {
    // Catches every other failed response (ClientException, ServerException)
    echo '   Request Error: HTTP '.$e->getResponse()->status()."\n";
} catch (FatalRequestException $e) {
    echo '   Connection Error: '.$e->getMessage()."\n";
}
echo "\n";

// 4. Handling missing site ID
echo "4. Handling Missing Site ID\n";
try {
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    // Note: We're not setting a site ID here

    echo "   Attempting to list devices without setting site ID...\n";
    $apiClient->devices()->listAdopted();
} catch (\RuntimeException $e) {
    // Thrown by the client before any request is sent
    echo '   Runtime Error: '.$e->getMessage()."\n";
    echo "   Solution: Call \$apiClient->setSiteId() before using site-specific endpoints.\n";
}
echo "\n";

// 5. Handling authentication errors
echo "5. Handling Authentication Errors\n";
try {
    $badClient = new UnifiClient($controllerUrl, 'invalid-api-key-12345', $verifySsl, throwOnErrors: true);

    echo "   Attempting to connect with an invalid API key...\n";
    $badClient->sites()->list();

    echo "   Unexpectedly succeeded.\n";
} catch (UnauthorizedException $e) {
    echo '   Authentication Error: Invalid API key (HTTP '.$e->getResponse()->status().")\n";
    echo "   Solution: Check your API key in the Integrations section of your UniFi application.\n";
} catch (ClientException $e) {
    echo '   Client Error: HTTP '.$e->getResponse()->status()."\n";
} catch (FatalRequestException $e) {
    echo '   Connection Error: '.$e->getMessage()."\n";
}
echo "\n";

// 6. Graceful error handling with fallback
echo "6. Graceful Error Handling with Fallback\n";
function getDevicesSafely(UnifiClient $apiClient): array
{
    try {
        $response = $apiClient->devices()->listAdopted();

        if ($response->failed()) {
            error_log('Error getting devices: HTTP '.$response->status().' '.$response->body());

            return [];
        }

        return $response->json('data') ?? [];
    } catch (FatalRequestException $e) {
        error_log('Connection error getting devices: '.$e->getMessage());

        return [];
    }
}

$apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
$apiClient->setSiteId($siteId);

$devices = getDevicesSafely($apiClient);
echo '   Retrieved '.count($devices)." devices (with error handling)\n\n";

echo str_repeat('=', 50)."\n";
echo "Error handling examples completed!\n\n";

echo "Summary:\n";
echo "- By default, 4xx/5xx responses are returned: check \$response->failed() or call \$response->throw()\n";
echo "- With throwOnErrors enabled, 4xx responses throw a ClientException and 5xx a ServerException\n";
echo "  (both extend RequestException; specific subclasses such as NotFoundException exist)\n";
echo "- FatalRequestException: network issues, timeouts and SSL errors (always thrown)\n";
echo "- RuntimeException: thrown before sending when a site ID is required but not set\n";
