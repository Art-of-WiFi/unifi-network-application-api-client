<?php
/**
 * Example 2: Device Management
 *
 * This example demonstrates how to work with UniFi devices including
 * listing devices, getting device details, statistics, and executing device actions.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$config = require_once __DIR__ . '/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$siteId        = $config['site_id'];
$verifySsl     = $config['verify_ssl'];

try {
    // Initialize the API client
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId($siteId);

    echo "UniFi API Client - Device Management Example\n";
    echo str_repeat('=', 50) . "\n\n";// 1. List all adopted devices
    echo "1. Listing all adopted devices...\n";

    $devicesResponse = $apiClient->devices()->listAdopted();
    $devices         = $devicesResponse->json();

    if (isset($devices['data']) && count($devices['data']) > 0) {
        foreach ($devices['data'] as $device) {
            $name  = $device['name'] ?? 'Unnamed';
            $model = $device['model'] ?? 'Unknown';
            $mac   = $device['mac'] ?? 'Unknown';
            $ip    = $device['ip'] ?? 'No IP';
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
        $deviceId    = $firstDevice['id'];
        $deviceName  = $firstDevice['name'] ?? 'Unnamed Device';

        // 2. Get detailed device information
        echo "2. Getting detailed information for: {$deviceName}\n";
        $detailResponse = $apiClient->devices()->get($deviceId);
        $details        = $detailResponse->json();

        echo "   IP Address: " . ($details['ipAddress'] ?? 'No IP') . "\n";
        echo "   Firmware: " . ($details['firmwareVersion'] ?? 'Unknown') . "\n";
        echo "   Adopted at: " . ($details['adoptedAt'] ?? 'Unknown') . "\n";
        echo "\n";

        // 3. Get device statistics
        echo "3. Getting device statistics...\n";
        $statsResponse = $apiClient->devices()->getStatistics($deviceId);
        $stats         = $statsResponse->json();

        echo "   CPU Usage: " . ($stats['cpu_usage'] ?? 'N/A') . "%\n";
        echo "   Memory Usage: " . ($stats['mem_usage'] ?? 'N/A') . "%\n";
        echo "   Temperature: " . ($stats['temperature'] ?? 'N/A') . "°C\n";
        echo "\n";

    } else {
        echo "   No adopted devices found.\n\n";
    }// 4. List pending devices (devices waiting to be adopted)
    echo "4. Listing pending devices...\n";
    $pendingResponse = $apiClient->devices()->listPending();
    $pending         = $pendingResponse->json();
    $pendingCount    = isset($pending['data']) ? count($pending['data']) : 0;
    echo "   Pending devices: {$pendingCount}\n";
    if ($pendingCount > 0) {
        foreach ($pending['data'] as $device) {
            $mac   = $device['mac'] ?? 'Unknown';
            $model = $device['model'] ?? 'Unknown';
            echo "   - {$model} (MAC: {$mac})\n";
        }
    }
    echo "\n";// 5. Filtering examples
    echo "5. Filtering Examples (based on official API filterable properties):\n";
    echo "   Available filterable properties:\n";
    echo "   - id (UUID), macAddress (STRING), ipAddress (STRING), name (STRING)\n";
    echo "   - model (STRING), state (STRING), supported (BOOLEAN)\n";
    echo "   - firmwareVersion (STRING), firmwareUpdatable (BOOLEAN)\n";
    echo "   - features (SET), interfaces (SET)\n\n";
    echo "   Filter devices by name pattern:\n";
    echo "   \$apiClient->devices()->listAdopted(filter: \"name.like('AP-%')\");\n\n";
    echo "   Filter by device model:\n";
    echo "   \$apiClient->devices()->listAdopted(filter: 'model.eq(\"U6-Pro\")');\n\n";
    echo "   Filter by state:\n";
    echo "   \$apiClient->devices()->listAdopted(filter: 'state.eq(\"ONLINE\")');\n\n";
    echo "   Filter devices needing firmware update:\n";
    echo "   \$apiClient->devices()->listAdopted(filter: 'firmwareUpdatable.eq(true)');\n\n";// 6. Example of executing device actions (commented out for safety)
    echo "6. Device Actions (examples - commented out for safety)\n";
    echo "   According to the official API specification, the only documented action is:\n";
    echo "   - Restart device:\n";
    echo "     \$apiClient->devices()->executeAction(\$deviceId, ['action' => 'RESTART']);\n";
    echo "\n";// Uncomment to actually execute an action:
    // $apiClient->devices()->executeAction($deviceId, ['action' => 'RESTART']);

    // 7. Adopting a pending device (commented out for safety)
    echo "7. Adopting a Device (example - commented out for safety)\n";
    echo "   Adopt a pending device by MAC address:\n";
    echo "   \$apiClient->devices()->adopt('00:11:22:33:44:55');\n\n";
    echo "   Adopt and ignore the device limit:\n";
    echo "   \$apiClient->devices()->adopt('00:11:22:33:44:55', ignoreDeviceLimit: true);\n\n";

    // Uncomment to actually adopt a device:
    // $apiClient->devices()->adopt('00:11:22:33:44:55');

    // 8. Removing (unadopting) a device (commented out for safety)
    echo "8. Removing a Device (example - commented out for safety)\n";
    echo "   Remove (unadopt) an adopted device:\n";
    echo "   \$apiClient->devices()->remove(\$deviceId);\n\n";

    // Uncomment to actually remove a device:
    // $apiClient->devices()->remove($deviceId);

    echo str_repeat('=', 50) . "\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
