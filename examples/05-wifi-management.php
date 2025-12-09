<?php
/**
 * Example 5: WiFi Management
 *
 * This example demonstrates how to manage WiFi broadcasts (SSIDs)
 * including listing, creating, updating, and deleting WiFi networks.
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

    echo "UniFi API Client - WiFi Management Example\n";
    echo str_repeat('=', 50) . "\n\n";// 1. List all WiFi broadcasts (SSIDs)
    echo "1. Listing all WiFi broadcasts...\n";

    $wifiResponse = $apiClient->wifiBroadcasts()->list();
    $wifiNetworks = $wifiResponse->json();

    if (isset($wifiNetworks['data']) && count($wifiNetworks['data']) > 0) {
        foreach ($wifiNetworks['data'] as $wifi) {
            $name     = $wifi['name'] ?? 'Unnamed SSID';
            $enabled  = ($wifi['enabled'] ?? false) ? 'Enabled' : 'Disabled';
            $security = $wifi['security'] ?? 'Unknown';
            $hidden   = ($wifi['is_hidden'] ?? false) ? 'Yes' : 'No';

            echo "   WiFi: {$name}\n";
            echo "   - Status: {$enabled}\n";
            echo "   - Security: {$security}\n";
            echo "   - Hidden: {$hidden}\n";
            echo "\n";
        }

        // Use the first WiFi network for detailed example
        if (count($wifiNetworks['data']) > 0) {
            $firstWifi = $wifiNetworks['data'][0];
            $wifiId    = $firstWifi['id'];
            $wifiName  = $firstWifi['name'] ?? 'Unnamed';

            // 2. Get detailed WiFi information
            echo "2. Getting detailed information for: {$wifiName}\n";
            $detailResponse = $apiClient->wifiBroadcasts()->get($wifiId);
            $details        = $detailResponse->json();

            echo "   Enabled: " . (($details['enabled'] ?? false) ? 'Yes' : 'No') . "\n";
            echo "   Security: " . ($details['security'] ?? 'Unknown') . "\n";
            echo "   WPA Mode: " . ($details['wpa_mode'] ?? 'N/A') . "\n";
            echo "   Guest Network: " . (($details['is_guest'] ?? false) ? 'Yes' : 'No') . "\n";
            echo "\n";
        }
    } else {
        echo "   No WiFi networks found.\n\n";
    }// 3. Example: Create a new WiFi network (commented out for safety)
    echo "3. Creating a New WiFi Network (example - commented out)\n";
    echo "   NOTE: WiFi broadcast creation has a complex structure in the API.\n";
    echo "   The API requires many fields including security configuration objects.\n";
    echo "   See the official API documentation for complete schema details.\n\n";
    echo "   Basic required fields include:\n";
    echo "   - type (STANDARD or IOT_OPTIMIZED)\n";
    echo "   - name\n";
    echo "   - enabled\n";
    echo "   - hideName\n";
    echo "   - clientIsolationEnabled\n";
    echo "   - multicastToUnicastConversionEnabled\n";
    echo "   - uapsdEnabled\n";
    echo "   - securityConfiguration (object with nested structure)\n";
    echo "   - broadcastingFrequenciesGHz (for STANDARD type)\n\n";
    echo "   Due to the complexity, we recommend using the UniFi Network Application UI\n";
    echo "   to create WiFi networks, or refer to the OpenAPI specification for the\n";
    echo "   complete securityConfiguration schema structure.\n\n";// Creating WiFi broadcasts requires complex nested objects - see API docs
    // $createResponse = $apiClient->wifiBroadcasts()->create([
    //     'type' => 'STANDARD',
    //     'name' => 'My WiFi',
    //     'enabled' => true,
    //     'hideName' => false,
    //     'clientIsolationEnabled' => false,
    //     'multicastToUnicastConversionEnabled' => false,
    //     'uapsdEnabled' => true,
    //     'advertiseDeviceName' => true,        // Required for STANDARD type
    //     'arpProxyEnabled' => false,           // Required for STANDARD type
    //     'bssTransitionEnabled' => true,       // Required for STANDARD type
    //     'broadcastingFrequenciesGHz' => ['2.4', '5'],  // Required for STANDARD type
    //     'securityConfiguration' => [/* complex nested object - see API docs */]
    // ]);
    // 4. Example: Update a WiFi network (commented out for safety)
    echo "4. Updating a WiFi Network (example - commented out)\n";
    echo "   Example code to enable/disable a WiFi network:\n";
    echo "   \$apiClient->wifiBroadcasts()->update(\$wifiId, [\n";
    echo "       'enabled' => false\n";
    echo "   ]);\n\n";
    echo "   Example code to change the WiFi name:\n";
    echo "   \$apiClient->wifiBroadcasts()->update(\$wifiId, [\n";
    echo "       'name' => 'Updated WiFi Name'\n";
    echo "   ]);\n\n";// Uncomment to actually update a WiFi network:
    // if (isset($wifiId)) {
    //     $updateResponse = $apiClient->wifiBroadcasts()->update($wifiId, [
    //         'enabled' => false
    //     ]);
    // }
    // 5. Example: Delete a WiFi network (commented out for safety)
    echo "5. Deleting a WiFi Network (example - commented out)\n";
    echo "   Example code to delete a WiFi network:\n";
    echo "   \$apiClient->wifiBroadcasts()->delete(\$wifiId);\n\n";
    echo "   To force delete (skip dependency checks):\n";
    echo "   \$apiClient->wifiBroadcasts()->delete(\$wifiId, force: true);\n\n";// Uncomment to actually delete a WiFi network:
    // $apiClient->wifiBroadcasts()->delete($wifiId);
    // 6. Notes on WiFi configuration
    echo "6. Notes on WiFi Configuration:\n\n";
    echo "   The UniFi API has a complex schema for WiFi broadcast creation/updates.\n";
    echo "   Key configuration areas include:\n\n";
    echo "   - Security: Configured via 'securityConfiguration' object\n";
    echo "     (supports OPEN, WPA_PERSONAL, WPA_ENTERPRISE, etc.)\n";
    echo "   - Network assignment: Via 'network' object (NATIVE or SPECIFIC network)\n";
    echo "   - Broadcasting: Frequencies (2.4GHz, 5GHz, 6GHz) and device filters\n";
    echo "   - Advanced: Band steering, client isolation, multicast filtering\n";
    echo "   - Hotspot: Captive portal or Passpoint configuration\n\n";
    echo "   For full schema details including security configuration and all\n";
    echo "   available options, please refer to the OpenAPI specification document.\n\n";
    echo str_repeat('=', 50) . "\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
