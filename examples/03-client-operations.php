<?php
/**
 * Example 3: Client Operations
 *
 * This example demonstrates how to work with connected clients,
 * including listing clients, filtering, and performing client actions.
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

echo "UniFi API Client - Client Operations Example\n";
echo str_repeat('=', 50) . "\n\n";

// 1. List all connected clients
echo "1. Listing all connected clients...\n";
$clientsResponse = $apiClient->clients()->list();
$clients = $clientsResponse->json();

$totalClients = isset($clients['data']) ? count($clients['data']) : 0;
echo "   Total connected clients: {$totalClients}\n\n";

if ($totalClients > 0) {
    // Categorize clients
    $wiredClients = [];
    $wirelessClients = [];
    $guestClients = [];

    foreach ($clients['data'] as $clientData) {
        if ($clientData['is_guest'] ?? false) {
            $guestClients[] = $clientData;
        } elseif ($clientData['is_wired'] ?? false) {
            $wiredClients[] = $clientData;
        } else {
            $wirelessClients[] = $clientData;
        }
    }

    echo "   Breakdown:\n";
    echo "   - Wired clients: " . count($wiredClients) . "\n";
    echo "   - Wireless clients: " . count($wirelessClients) . "\n";
    echo "   - Guest clients: " . count($guestClients) . "\n\n";

    // 2. Display wireless client details
    if (count($wirelessClients) > 0) {
        echo "2. Wireless Client Details (first 5):\n";
        $displayCount = min(5, count($wirelessClients));

        for ($i = 0; $i < $displayCount; $i++) {
            $client = $wirelessClients[$i];
            $hostname = $client['hostname'] ?? $client['mac'] ?? 'Unknown';
            $mac = $client['mac'] ?? 'Unknown';
            $ip = $client['ip'] ?? 'No IP';
            $ssid = $client['essid'] ?? 'Unknown SSID';
            $signal = $client['signal'] ?? 0;

            echo "   Client: {$hostname}\n";
            echo "   - MAC: {$mac}\n";
            echo "   - IP: {$ip}\n";
            echo "   - SSID: {$ssid}\n";
            echo "   - Signal: {$signal} dBm\n";
            echo "\n";
        }
    }

    // 3. Get specific client details
    if ($totalClients > 0) {
        $firstClient = $clients['data'][0];
        $clientId = $firstClient['id'] ?? $firstClient['mac'];

        echo "3. Getting detailed information for a specific client...\n";
        $detailResponse = $apiClient->clients()->get($clientId);
        $details = $detailResponse->json();

        if (isset($details['data'])) {
            $data = $details['data'];
            echo "   Hostname: " . ($data['hostname'] ?? 'Unknown') . "\n";
            echo "   MAC: " . ($data['mac'] ?? 'Unknown') . "\n";
            echo "   IP: " . ($data['ip'] ?? 'No IP') . "\n";
            echo "   First Seen: " . ($data['first_seen'] ?? 'Unknown') . "\n";
            echo "   Last Seen: " . ($data['last_seen'] ?? 'Unknown') . "\n";
        }
        echo "\n";
    }
}

// 4. Filtering examples
echo "4. Filtering Examples:\n";
echo "   Get only wireless clients:\n";
echo "   \$apiClient->clients()->list(filter: 'is_wired.eq(false)');\n\n";

echo "   Get only guest clients:\n";
echo "   \$apiClient->clients()->list(filter: 'is_guest.eq(true)');\n\n";

echo "   Get clients connected to a specific SSID:\n";
echo "   \$apiClient->clients()->list(filter: \"essid.eq('Guest WiFi')\");\n\n";

// Example: Get only wireless clients
$wirelessResponse = $apiClient->clients()->list(filter: 'is_wired.eq(false)');
$wirelessData = $wirelessResponse->json();
$wirelessCount = isset($wirelessData['data']) ? count($wirelessData['data']) : 0;
echo "   Filtered wireless clients: {$wirelessCount}\n\n";

// 5. Client actions
echo "5. Client Actions (examples - commented out for safety)\n";
echo "   Available actions:\n";
echo "   - Authorize a guest client:\n";
echo "     \$apiClient->clients()->executeAction(\$clientId, [\n";
echo "         'action' => 'authorize-guest',\n";
echo "         'minutes' => 480  // 8 hours\n";
echo "     ]);\n";
echo "\n";
echo "   - Unauthorize a guest client:\n";
echo "     \$apiClient->clients()->executeAction(\$clientId, [\n";
echo "         'action' => 'unauthorize-guest'\n";
echo "     ]);\n";
echo "\n";
echo "   - Block a client:\n";
echo "     \$apiClient->clients()->executeAction(\$clientId, [\n";
echo "         'action' => 'block'\n";
echo "     ]);\n";
echo "\n";
echo "   - Unblock a client:\n";
echo "     \$apiClient->clients()->executeAction(\$clientId, [\n";
echo "         'action' => 'unblock'\n";
echo "     ]);\n";
echo "\n";

echo str_repeat('=', 50) . "\n";
echo "Example completed successfully!\n";
