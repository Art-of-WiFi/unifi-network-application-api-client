<?php
/**
 * Example 1: Basic Usage
 *
 * This example demonstrates the basic usage of the UniFi API client,
 * including initializing the client, listing sites, and retrieving basic information.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$config = require_once __DIR__ . '/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$verifySsl     = $config['verify_ssl'];

try {
    // Initialize the API client
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);

    echo str_repeat('=', 50) . "\n";
    echo "UniFi API Client - Basic Usage Example\n";
    echo str_repeat('=', 50) . "\n\n";// Get application information
    echo "1. Getting application information...\n";

    $appInfo = $apiClient->applicationInfo()->get();
    $info    = $appInfo->json();

    echo "   Controller Version: " . ($info['applicationVersion'] ?? 'Unknown') . "\n";
    echo "   Status: Connected\n\n";// List all sites
    echo "2. Listing all sites...\n";

    $sitesResponse = $apiClient->sites()->list();
    $sites         = $sitesResponse->json();

    if (isset($sites['data']) && count($sites['data']) > 0) {
        foreach ($sites['data'] as $site) {
            echo '   ' . str_repeat('-', 50) . "\n";
            echo "   Site: {$site['name']}\n";
            echo '   ' . str_repeat('-', 50) . "\n";
            echo "   ID: {$site['id']})\n";
            echo "   Description: " . ($site['description'] ?? 'No description') . "\n";

            $siteId = $site['id'];

            // Set the site context
            $apiClient->setSiteId($siteId);

            // Get device count
            echo PHP_EOL;
            echo "   - Getting device information...\n";
            $devicesResponse = $apiClient->devices()->listAdopted(limit: 100);
            $devices         = $devicesResponse->json();

            $deviceCount = isset($devices['data']) ? count($devices['data']) : 0;
            echo "     Total adopted devices: {$deviceCount}\n";

            if ($deviceCount > 0) {
                echo "     Devices:\n";
                foreach ($devices['data'] as $device) {
                    $name     = $device['name'] ?? 'Unnamed Device';
                    $model    = $device['model'] ?? 'Unknown Model';
                    $state    = $device['state'] ?? 'Unknown State';
                    $firmware = $device['firmwareVersion'] ?? 'Unknown Firmware';
                    echo "     - {$name} ({$model}, {$state}, {$firmware})\n";
                }
            }

            // Get client count
            echo "\n   - Getting client information...\n";
            $clientsResponse = $apiClient->clients()->list(limit: 100);
            $clients         = $clientsResponse->json();

            $clientCount = isset($clients['data']) ? count($clients['data']) : 0;
            echo "     Total connected clients: {$clientCount}\n";

            if ($clientCount > 0) {
                echo "     Clients:\n";
                $displayCount = min(5, $clientCount);
                for ($i = 0; $i < $displayCount; $i++) {
                    $client     = $clients['data'][$i];
                    $hostname   = $client['hostname'] ?? $client['mac'] ?? 'Unknown';
                    $connection = ($client['is_wired'] ?? null) ? 'Wired' : 'Wireless';
                    echo "     - {$hostname} ({$connection})\n";
                }
                if ($clientCount > 5) {
                    echo '   ... and ' . ($clientCount - 5) . " more\n";
                }
            }

            echo PHP_EOL;
        }
    } else {
        echo "   No sites found or error retrieving sites.\n";
    }

    echo str_repeat('=', 50) . "\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
