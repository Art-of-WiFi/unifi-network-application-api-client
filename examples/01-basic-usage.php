<?php
/**
 * Example 1: Basic Usage
 *
 * This example demonstrates the basic usage of the UniFi API client,
 * including initializing the client, listing sites, and retrieving basic information.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$verifySsl     = $config['verify_ssl'];

// Initialize the API client
$apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);

echo "UniFi API Client - Basic Usage Example\n";
echo str_repeat('=', 50) . "\n\n";

// Get application information
echo "1. Getting application information...\n";
$appInfo = $apiClient->applicationInfo()->get();
$info = $appInfo->json();

echo "   Controller Version: " . ($info['version'] ?? 'Unknown') . "\n";
echo "   Status: Connected\n\n";

// List all sites
echo "2. Listing all sites...\n";
$sitesResponse = $apiClient->sites()->list();
$sites = $sitesResponse->json();

if (isset($sites['data']) && count($sites['data']) > 0) {
    foreach ($sites['data'] as $site) {
        echo "   - {$site['name']} (ID: {$site['id']})\n";
        echo "     Description: " . ($site['description'] ?? 'No description') . "\n";
    }

    // Use the first site for the next examples
    $firstSite = $sites['data'][0];
    $siteId = $firstSite['id'];
    $siteName = $firstSite['name'];

    echo "\n3. Using site: {$siteName}\n";

    // Set the site context
    $apiClient->setSiteId($siteId);

    // Get device count
    echo "4. Getting device information...\n";
    $devicesResponse = $apiClient->devices()->listAdopted(limit: 100);
    $devices = $devicesResponse->json();

    $deviceCount = isset($devices['data']) ? count($devices['data']) : 0;
    echo "   Total adopted devices: {$deviceCount}\n";

    if ($deviceCount > 0) {
        echo "   Devices:\n";
        foreach ($devices['data'] as $device) {
            $name = $device['name'] ?? 'Unnamed Device';
            $model = $device['model'] ?? 'Unknown Model';
            $type = $device['type'] ?? 'Unknown Type';
            echo "   - {$name} ({$model}, {$type})\n";
        }
    }

    // Get client count
    echo "\n5. Getting client information...\n";
    $clientsResponse = $apiClient->clients()->list(limit: 100);
    $clients = $clientsResponse->json();

    $clientCount = isset($clients['data']) ? count($clients['data']) : 0;
    echo "   Total connected clients: {$clientCount}\n";

    if ($clientCount > 0) {
        echo "   Clients:\n";
        $displayCount = min(5, $clientCount);
        for ($i = 0; $i < $displayCount; $i++) {
            $client = $clients['data'][$i];
            $hostname = $client['hostname'] ?? $client['mac'] ?? 'Unknown';
            $connection = ($client['is_wired'] ?? null) ? 'Wired' : 'Wireless';
            echo "   - {$hostname} ({$connection})\n";
        }
        if ($clientCount > 5) {
            echo "   ... and " . ($clientCount - 5) . " more\n";
        }
    }
} else {
    echo "   No sites found or error retrieving sites.\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "Example completed successfully!\n";
