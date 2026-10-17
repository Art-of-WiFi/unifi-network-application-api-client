<?php

/**
 * Example 5: WiFi Management
 *
 * This example demonstrates how to manage WiFi broadcasts (SSIDs)
 * including listing, creating, updating, and deleting WiFi networks.
 */

require_once __DIR__.'/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\WifiBroadcasts\WifiBroadcastFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$config = require_once __DIR__.'/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey = $config['api_key'];
$siteId = $config['site_id'];
$verifySsl = $config['verify_ssl'];

try {
    // Initialize the API client
    $apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);
    $apiClient->setSiteId($siteId);

    echo "UniFi API Client - WiFi Management Example\n";
    echo str_repeat('=', 50)."\n\n";

    // 1. List all WiFi broadcasts (SSIDs)
    echo "1. Listing all WiFi broadcasts...\n";

    $wifiResponse = $apiClient->wifiBroadcasts()->list();
    $wifiNetworks = $wifiResponse->json();

    if (isset($wifiNetworks['data']) && count($wifiNetworks['data']) > 0) {
        foreach ($wifiNetworks['data'] as $wifi) {
            $name = $wifi['name'] ?? 'Unnamed SSID';
            $type = $wifi['type'] ?? 'Unknown';
            $enabled = ($wifi['enabled'] ?? false) ? 'Enabled' : 'Disabled';
            $security = $wifi['securityConfiguration']['type'] ?? 'Unknown';
            $frequencies = implode(', ', $wifi['broadcastingFrequenciesGHz'] ?? []);

            echo "   WiFi: {$name} ({$type})\n";
            echo "   - Status: {$enabled}\n";
            echo "   - Security: {$security}\n";
            echo '   - Frequencies: '.($frequencies !== '' ? "{$frequencies} GHz" : 'N/A')."\n";
            echo "\n";
        }

        // Use the first WiFi network for detailed example
        $firstWifi = $wifiNetworks['data'][0];
        $wifiId = $firstWifi['id'];
        $wifiName = $firstWifi['name'] ?? 'Unnamed';

        // 2. Get detailed WiFi information
        // Note: single-item responses return the object directly, without a 'data' wrapper
        echo "2. Getting detailed information for: {$wifiName}\n";
        $detailResponse = $apiClient->wifiBroadcasts()->get($wifiId);
        $details = $detailResponse->json();

        echo '   Enabled: '.(($details['enabled'] ?? false) ? 'Yes' : 'No')."\n";
        echo '   Security: '.($details['securityConfiguration']['type'] ?? 'Unknown')."\n";
        echo '   Network: '.($details['network']['type'] ?? 'N/A')."\n";
        echo '   Hidden SSID: '.(($details['hideName'] ?? false) ? 'Yes' : 'No')."\n";
        echo '   Client Isolation: '.(($details['clientIsolationEnabled'] ?? false) ? 'Yes' : 'No')."\n";
        echo '   Hotspot: '.($details['hotspotConfiguration']['type'] ?? 'None')."\n";

        // Fields added in Network 10.6.106 - not present on older versions
        if (array_key_exists('channel2gLockedTo6', $details)) {
            echo '   2.4 GHz channel locked to 6: '.($details['channel2gLockedTo6'] ? 'Yes' : 'No')."\n";
            echo '   2.4 GHz DTIM locked to 3: '.(($details['dtimPeriod2gLockedTo3'] ?? false) ? 'Yes' : 'No')."\n";
            echo '   DNS assistance: '.($details['dnsAssistanceConfiguration']['mode'] ?? 'Disabled')."\n";
            echo '   Handoff suggestions: '.(isset($details['handoffSuggestionsConfiguration']) ? 'Enabled' : 'Disabled')."\n";
        }
        echo "\n";
    } else {
        echo "   No WiFi networks found.\n\n";
    }

    // 3. Filtering WiFi broadcasts
    echo "3. Filtering WiFi broadcasts\n";
    $sixGhzResponse = $apiClient->wifiBroadcasts()->list(
        filter: WifiBroadcastFilter::broadcastingFrequenciesGHz()->contains(6)
    );
    echo '   Broadcasts on 6 GHz: '.($sixGhzResponse->json('totalCount') ?? 0)."\n\n";

    // 4. Example: Create a new WiFi network (commented out for safety)
    echo "4. Creating a New WiFi Network (example - commented out)\n";
    echo "   \$apiClient->wifiBroadcasts()->create([\n";
    echo "       'type' => 'STANDARD',\n";
    echo "       'name' => 'My WiFi',\n";
    echo "       'enabled' => true,\n";
    echo "       'network' => ['type' => 'NATIVE'],\n";
    echo "       'securityConfiguration' => [\n";
    echo "           'type' => 'WPA2_WPA3_PERSONAL',\n";
    echo "           'passphrase' => 'a-strong-passphrase',\n";
    echo "           'pmfMode' => 'OPTIONAL',\n";
    echo "           'wpa3FastRoamingEnabled' => false,\n";
    echo "           'saeConfiguration' => ['anticloggingThresholdSeconds' => 5, 'syncTimeSeconds' => 5],\n";
    echo "       ],\n";
    echo "       'broadcastingFrequenciesGHz' => [2.4, 5],\n";
    echo "       'hideName' => false,\n";
    echo "       'clientIsolationEnabled' => false,\n";
    echo "       'multicastToUnicastConversionEnabled' => false,\n";
    echo "       'uapsdEnabled' => false,\n";
    echo "       'advertiseDeviceName' => false,\n";
    echo "       'arpProxyEnabled' => false,\n";
    echo "       'bssTransitionEnabled' => true,\n";
    echo "       'channel2gLockedTo6' => false,     // required as of Network 10.6.106\n";
    echo "       'dtimPeriod2gLockedTo3' => false,  // required as of Network 10.6.106\n";
    echo "   ]);\n\n";

    // Uncomment to actually create a WiFi network:
    // $createResponse = $apiClient->wifiBroadcasts()->create([
    //     'type' => 'STANDARD',
    //     'name' => 'My WiFi',
    //     'enabled' => true,
    //     'network' => ['type' => 'NATIVE'],
    //     'securityConfiguration' => [
    //         'type' => 'WPA2_WPA3_PERSONAL',
    //         'passphrase' => 'a-strong-passphrase',
    //         'pmfMode' => 'OPTIONAL',
    //         'wpa3FastRoamingEnabled' => false,
    //         'saeConfiguration' => ['anticloggingThresholdSeconds' => 5, 'syncTimeSeconds' => 5],
    //     ],
    //     'broadcastingFrequenciesGHz' => [2.4, 5],
    //     'hideName' => false,
    //     'clientIsolationEnabled' => false,
    //     'multicastToUnicastConversionEnabled' => false,
    //     'uapsdEnabled' => false,
    //     'advertiseDeviceName' => false,
    //     'arpProxyEnabled' => false,
    //     'bssTransitionEnabled' => true,
    //     'channel2gLockedTo6' => false,
    //     'dtimPeriod2gLockedTo3' => false,
    // ]);

    // 5. Example: Update a WiFi network (commented out for safety)
    echo "5. Updating a WiFi Network (example - commented out)\n";
    echo "   Updates use PUT, so the body must contain all required fields (the same as for\n";
    echo "   create()), not only the fields you want to change.\n\n";

    // 6. Example: Delete a WiFi network (commented out for safety)
    echo "6. Deleting a WiFi Network (example - commented out)\n";
    echo "   Example code to delete a WiFi network:\n";
    echo "   \$apiClient->wifiBroadcasts()->delete(\$wifiId);\n\n";
    echo "   To force delete (skip dependency checks):\n";
    echo "   \$apiClient->wifiBroadcasts()->delete(\$wifiId, force: true);\n\n";

    // Uncomment to actually delete a WiFi network:
    // $apiClient->wifiBroadcasts()->delete($wifiId);

    // 7. Notes on WiFi configuration
    echo "7. Notes on WiFi Configuration:\n\n";
    echo "   - Security: 'securityConfiguration' object with a 'type' of OPEN, WPA2_PERSONAL,\n";
    echo "     WPA3_PERSONAL, WPA2_WPA3_PERSONAL, WPA2_ENTERPRISE, WPA3_ENTERPRISE or\n";
    echo "     WPA2_WPA3_ENTERPRISE. OPEN supports Enhanced Open via 'encryption' (Network 10.6.106+)\n";
    echo "   - Network assignment: 'network' object (NATIVE or SPECIFIC with a networkId)\n";
    echo "   - Broadcasting: frequencies (2.4, 5, 6 GHz) and an optional device or device tag filter\n";
    echo "   - Advanced: band steering, client isolation, multicast filtering, mDNS proxy\n";
    echo "   - Network 10.6.106+: DNS assistance and handoff suggestions (STANDARD broadcasts)\n";
    echo "   - Hotspot: captive portal or Passpoint configuration\n\n";
    echo "   See the OpenAPI specification at developer.ui.com for the full schema.\n\n";

    echo str_repeat('=', 50)."\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
