<?php
/**
 * Example 5: WiFi Management
 *
 * This example demonstrates how to manage WiFi broadcasts (SSIDs)
 * including listing, creating, updating, and deleting WiFi networks.
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

echo "UniFi API Client - WiFi Management Example\n";
echo str_repeat('=', 50) . "\n\n";

// 1. List all WiFi broadcasts (SSIDs)
echo "1. Listing all WiFi broadcasts...\n";
$wifiResponse = $apiClient->wifiBroadcasts()->list();
$wifiNetworks = $wifiResponse->json();

if (isset($wifiNetworks['data']) && count($wifiNetworks['data']) > 0) {
    foreach ($wifiNetworks['data'] as $wifi) {
        $name = $wifi['name'] ?? 'Unnamed SSID';
        $enabled = ($wifi['enabled'] ?? false) ? 'Enabled' : 'Disabled';
        $security = $wifi['security'] ?? 'Unknown';
        $hidden = ($wifi['is_hidden'] ?? false) ? 'Yes' : 'No';

        echo "   WiFi: {$name}\n";
        echo "   - Status: {$enabled}\n";
        echo "   - Security: {$security}\n";
        echo "   - Hidden: {$hidden}\n";
        echo "\n";
    }

    // Use the first WiFi network for detailed example
    if (count($wifiNetworks['data']) > 0) {
        $firstWifi = $wifiNetworks['data'][0];
        $wifiId = $firstWifi['id'];
        $wifiName = $firstWifi['name'] ?? 'Unnamed';

        // 2. Get detailed WiFi information
        echo "2. Getting detailed information for: {$wifiName}\n";
        $detailResponse = $apiClient->wifiBroadcasts()->get($wifiId);
        $details = $detailResponse->json();

        if (isset($details['data'])) {
            $data = $details['data'];
            echo "   Enabled: " . (($data['enabled'] ?? false) ? 'Yes' : 'No') . "\n";
            echo "   Security: " . ($data['security'] ?? 'Unknown') . "\n";
            echo "   WPA Mode: " . ($data['wpa_mode'] ?? 'N/A') . "\n";
            echo "   Guest Network: " . (($data['is_guest'] ?? false) ? 'Yes' : 'No') . "\n";
        }
        echo "\n";
    }
} else {
    echo "   No WiFi networks found.\n\n";
}

// 3. Example: Create a new WiFi network (commented out for safety)
echo "3. Creating a New WiFi Network (example - commented out)\n";
echo "   Example code to create a guest WiFi network:\n";
echo "   \$apiClient->wifiBroadcasts()->create([\n";
echo "       'name' => 'Guest WiFi',\n";
echo "       'enabled' => true,\n";
echo "       'security' => 'wpa2',\n";
echo "       'wpa_mode' => 'wpa2',\n";
echo "       'password' => 'SecurePassword123',\n";
echo "       'is_guest' => true,\n";
echo "       'is_hidden' => false,\n";
echo "       'band_steering' => true,\n";
echo "       'multicast_enhance' => false\n";
echo "   ]);\n\n";

// Uncomment to actually create a WiFi network:
// $createResponse = $apiClient->wifiBroadcasts()->create([
//     'name' => 'API Test WiFi',
//     'enabled' => true,
//     'security' => 'wpa2',
//     'password' => 'TestPassword123',
//     'is_guest' => false
// ]);

// 4. Example: Update a WiFi network (commented out for safety)
echo "4. Updating a WiFi Network (example - commented out)\n";
echo "   Example code to change the WiFi password:\n";
echo "   \$apiClient->wifiBroadcasts()->update(\$wifiId, [\n";
echo "       'password' => 'NewSecurePassword456'\n";
echo "   ]);\n\n";

echo "   Example code to enable/disable a WiFi network:\n";
echo "   \$apiClient->wifiBroadcasts()->update(\$wifiId, [\n";
echo "       'enabled' => false\n";
echo "   ]);\n\n";

// Uncomment to actually update a WiFi network:
// if (isset($wifiId)) {
//     $updateResponse = $apiClient->wifiBroadcasts()->update($wifiId, [
//         'password' => 'UpdatedPassword789'
//     ]);
// }

// 5. Example: Delete a WiFi network (commented out for safety)
echo "5. Deleting a WiFi Network (example - commented out)\n";
echo "   Example code to delete a WiFi network:\n";
echo "   \$apiClient->wifiBroadcasts()->delete(\$wifiId);\n\n";
echo "   To force delete (skip dependency checks):\n";
echo "   \$apiClient->wifiBroadcasts()->delete(\$wifiId, force: true);\n\n";

// Uncomment to actually delete a WiFi network:
// $apiClient->wifiBroadcasts()->delete($wifiId);

// 6. Common WiFi configurations
echo "6. Common WiFi Configuration Examples:\n\n";

echo "   a) Corporate WiFi with WPA2 Enterprise:\n";
echo "   \$apiClient->wifiBroadcasts()->create([\n";
echo "       'name' => 'Corporate WiFi',\n";
echo "       'security' => 'wpa2-enterprise',\n";
echo "       'wpa_mode' => 'wpa2',\n";
echo "       'radius_profile_id' => 'your-radius-profile-id',\n";
echo "       'enabled' => true\n";
echo "   ]);\n\n";

echo "   b) Guest WiFi with Portal:\n";
echo "   \$apiClient->wifiBroadcasts()->create([\n";
echo "       'name' => 'Guest Portal WiFi',\n";
echo "       'security' => 'open',\n";
echo "       'is_guest' => true,\n";
echo "       'portal_enabled' => true,\n";
echo "       'portal_customization_id' => 'your-portal-id',\n";
echo "       'enabled' => true\n";
echo "   ]);\n\n";

echo "   c) Hidden WiFi Network:\n";
echo "   \$apiClient->wifiBroadcasts()->create([\n";
echo "       'name' => 'Hidden Network',\n";
echo "       'security' => 'wpa2',\n";
echo "       'password' => 'SecurePassword',\n";
echo "       'is_hidden' => true,\n";
echo "       'enabled' => true\n";
echo "   ]);\n\n";

echo str_repeat('=', 50) . "\n";
echo "Example completed successfully!\n";
