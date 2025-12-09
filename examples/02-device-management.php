<?php
/**
 * Example 2: Device Management
 *
 * This example demonstrates how to work with UniFi devices including
 * listing devices, getting device details, statistics, and executing device actions.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$siteId        = $config['site_id'];
$verifySsl     = $config['verify_ssl'];

// Initialize the API client
$apiClient = new UnifiClient($controllerUrl, $apiKey, false);
$apiClient->setSiteId($siteId);

echo "UniFi API Client - Device Management Example\n";
echo str_repeat('=', 50) . "\n\n";

// 1. List all adopted devices
echo "1. Listing all adopted devices...\n";
$devicesResponse = $apiClient->devices()->listAdopted();
$devices = $devicesResponse->json();

if (isset($devices['data']) && count($devices['data']) > 0) {
    foreach ($devices['data'] as $device) {
        $name = $device['name'] ?? 'Unnamed';
        $model = $device['model'] ?? 'Unknown';
        $mac = $device['mac'] ?? 'Unknown';
        $ip = $device['ip'] ?? 'No IP';
        $state = $device['state'] ?? 'Unknown';

        echo "   Device: {$name}\n";
        echo "   - Model: {$model}\n";
        echo "   - MAC: {$mac}\n";
        echo "   - IP: {$ip}\n";
        echo "   - State: {$state}\n";
        echo "\n";
    }

    // Use the first device for detailed examples
    $firstDevice = $devices['data'][0];
    $deviceId = $firstDevice['id'];
    $deviceName = $firstDevice['name'] ?? 'Unnamed Device';

    // 2. Get detailed device information
    echo "2. Getting detailed information for: {$deviceName}\n";
    $detailResponse = $apiClient->devices()->get($deviceId);
    $details = $detailResponse->json();

    echo "   Firmware: " . ($details['version'] ?? 'Unknown') . "\n";
    echo "   Uptime: " . ($details['uptime'] ?? 0) . " seconds\n";
    echo "   Adopted: " . (($details['adopted'] ?? false) ? 'Yes' : 'No') . "\n";
    echo "\n";

    // 3. Get device statistics
    echo "3. Getting device statistics...\n";
    $statsResponse = $apiClient->devices()->getStatistics($deviceId);
    $stats = $statsResponse->json();

    if (isset($stats['data'])) {
        echo "   CPU Usage: " . ($stats['data']['cpu_usage'] ?? 'N/A') . "%\n";
        echo "   Memory Usage: " . ($stats['data']['mem_usage'] ?? 'N/A') . "%\n";
        echo "   Temperature: " . ($stats['data']['temperature'] ?? 'N/A') . "°C\n";
    }
    echo "\n";

} else {
    echo "   No adopted devices found.\n\n";
}

// 4. List pending devices (devices waiting to be adopted)
echo "4. Listing pending devices...\n";
$pendingResponse = $apiClient->devices()->listPending();
$pending = $pendingResponse->json();

$pendingCount = isset($pending['data']) ? count($pending['data']) : 0;
echo "   Pending devices: {$pendingCount}\n";

if ($pendingCount > 0) {
    foreach ($pending['data'] as $device) {
        $mac = $device['mac'] ?? 'Unknown';
        $model = $device['model'] ?? 'Unknown';
        echo "   - {$model} (MAC: {$mac})\n";
    }
}
echo "\n";

// 5. Example of executing device actions (commented out for safety)
echo "5. Device Actions (examples - commented out for safety)\n";
echo "   Available actions:\n";
echo "   - Reboot device:\n";
echo "     \$apiClient->devices()->executeAction(\$deviceId, ['action' => 'reboot']);\n";
echo "\n";
echo "   - Locate device (LED blink):\n";
echo "     \$apiClient->devices()->executeAction(\$deviceId, ['action' => 'locate']);\n";
echo "\n";
echo "   - Upgrade firmware:\n";
echo "     \$apiClient->devices()->executeAction(\$deviceId, ['action' => 'upgrade']);\n";
echo "\n";

// Uncomment to actually execute an action:
// $apiClient->devices()->executeAction($deviceId, ['action' => 'locate']);

echo str_repeat('=', 50) . "\n";
echo "Example completed successfully!\n";
