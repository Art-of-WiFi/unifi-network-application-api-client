<?php
/**
 * Example 3: Client Operations
 *
 * This example demonstrates how to work with connected clients,
 * including listing clients, filtering, and performing client actions.
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

    echo "UniFi API Client - Client Operations Example\n";
    echo str_repeat('=', 50) . "\n\n";// 1. List all connected clients
    echo "1. Listing all connected clients...\n";

    $clientsResponse = $apiClient->clients()->list();
    $clients         = $clientsResponse->json();
    $totalClients    = isset($clients['data']) ? count($clients['data']) : 0;

    echo "   Total connected clients: {$totalClients}\n\n";

    if ($totalClients > 0) {
        // Categorize clients
        $wiredClients    = [];
        $wirelessClients = [];
        $vpnClients      = [];
        $teleportClients = [];

        foreach ($clients['data'] as $clientData) {
            if ($clientData['type'] === 'WIRED') {
                $wiredClients[] = $clientData;
            } elseif ($clientData['type'] === 'WIRELESS') {
                $wirelessClients[] = $clientData;
            } elseif ($clientData['type'] === 'VPN') {
                $vpnClients[] = $clientData;
            } else {
                $teleportClients[] = $clientData;
            }
        }

        echo "   Breakdown:\n";
        echo "   - Wired clients: " . count($wiredClients) . "\n";
        echo "   - Wireless clients: " . count($wirelessClients) . "\n";
        echo "   - VPN clients: " . count($vpnClients) . "\n";
        echo "   - Teleport clients: " . count($teleportClients) . "\n\n";

        // 2. Display wireless client details
        if (count($wirelessClients) > 0) {
            echo "2. Wireless Client Details (first 5):\n";
            $displayCount = min(5, count($wirelessClients));

            for ($i = 0; $i < $displayCount; $i++) {
                $client   = $wirelessClients[$i];
                $name        = $client['name'] ?? $client['macAddress'] ?? 'Unknown';
                $mac         = $client['macAddress'] ?? 'Unknown';
                $ip          = $client['ipAddress'] ?? 'No IP';
                $connectedAt = $client['connectedAt'] ?? 'Unknown';

                echo "   Client: {$name}\n";
                echo "   - MAC: {$mac}\n";
                echo "   - IP: {$ip}\n";
                echo "   - Connected at: {$connectedAt}\n";
                echo "\n";
            }
        }

        // 3. Get specific client details
        if ($totalClients > 0) {
            $firstClient = $clients['data'][0];
            $clientId    = $firstClient['id'];

            echo "3. Getting detailed information for a specific client...\n";
            echo "   Client ID: {$clientId}\n";
            $detailResponse = $apiClient->clients()->get($clientId);

            try {
                $details = $detailResponse->json();
            } catch (Exception $e) {
                echo "   Error: " . $e->getMessage() . "\n";
            }

            echo "   Hostname: " . ($details['name'] ?? 'Unknown') . "\n";
            echo "   IP: " . ($details['ipAddress'] ?? 'No IP') . "\n";
            echo "   Connected at: " . ($details['connectedAt'] ?? 'Unknown') . "\n";
            echo "   Access type: " . ($details['access']['type'] ?? 'Unknown') . "\n";

            echo "\n";
        }
    }// 4. Filtering examples
    echo "4. Filtering Examples (based on official API filterable properties):\n";
    echo "   Available filterable properties:\n";
    echo "   - id (UUID), type (STRING), macAddress (STRING), ipAddress (STRING)\n";
    echo "   - connectedAt (TIMESTAMP), access.type (STRING), access.authorized (BOOLEAN)\n\n";
    echo "   Get only wireless clients:\n";
    echo "   \$apiClient->clients()->list(filter: 'type.eq(\"WIRELESS\")');\n\n";
    echo "   Get only wired clients:\n";
    echo "   \$apiClient->clients()->list(filter: 'type.eq(\"WIRED\")');\n\n";
    echo "   Get only guest clients:\n";
    echo "   \$apiClient->clients()->list(filter: 'access.type.eq(\"GUEST\")');\n\n";
    echo "   Get clients by IP address:\n";
    echo "   \$apiClient->clients()->list(filter: 'ipAddress.eq(\"192.168.1.100\")');\n\n";
    echo "   Get recently connected clients (last hour):\n";

    $timestamp = (new DateTime('-1 hour'))->format('Y-m-d\\TH:i:s\\Z');

    echo "   \$apiClient->clients()->list(filter: \"connectedAt.gt(\\$timestamp)\");\n\n";// Example: Get only wireless clients

    $wirelessResponse = $apiClient->clients()->list(filter: 'type.eq("WIRELESS")');
    $wirelessData     = $wirelessResponse->json();
    $wirelessCount    = isset($wirelessData['data']) ? count($wirelessData['data']) : 0;

    echo "   Filtered wireless clients: {$wirelessCount}\n\n";// 5. Client actions
    echo "5. Client Actions (examples - commented out for safety)\n";
    echo "   According to the official API specification, the only documented actions are:\n";
    echo "   - Authorize a guest client:\n";
    echo "     \$apiClient->clients()->executeAction(\$clientId, [\n";
    echo "         'action' => 'AUTHORIZE_GUEST_ACCESS',\n";
    echo "         'timeLimitMinutes' => 480,  // Optional: 8 hours (range: 1-1000000)\n";
    echo "         'dataUsageLimitMBytes' => 1024  // Optional: 1GB data limit (range: 1-1048576)\n";
    echo "         'rxRateLimitKbps' => 100000  // Optional: download rate limit in kilobits per second (range: 2-100000)\n";
    echo "         'txRateLimitKbps' => 100000  // Optional: upload rate limit in kilobits per second (range: 2-100000)\n";
    echo "     ]);\n";
    echo "\n";
    echo "   - Unauthorize a guest client:\n";
    echo "     \$apiClient->clients()->executeAction(\$clientId, [\n";
    echo "         'action' => 'UNAUTHORIZE_GUEST_ACCESS'\n";
    echo "     ]);\n";
    echo "\n";
    echo str_repeat('=', 50) . "\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
