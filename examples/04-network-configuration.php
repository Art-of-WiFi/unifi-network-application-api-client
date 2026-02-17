<?php

/**
 * Example 4: Network Configuration
 *
 * This example demonstrates how to manage networks including
 * listing, creating, updating, and deleting network configurations.
 */

require_once __DIR__.'/../vendor/autoload.php';

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

    echo "UniFi API Client - Network Configuration Example\n";
    echo str_repeat('=', 50)."\n\n"; // 1. List all networks
    echo "1. Listing all networks...\n";

    $networksResponse = $apiClient->networks()->list();

    try {
        $networks = $networksResponse->json();
    } catch (Exception $e) {
        echo '   Error: '.$e->getMessage()."\n";
    }

    if (isset($networks['data']) && count($networks['data']) > 0) {
        foreach ($networks['data'] as $network) {
            $name = $network['name'] ?? 'Unnamed Network';
            $management = $network['management'] ?? 'Unknown';
            $vlan = $network['vlanId'] ?? 'None';
            $networkId = $network['id'] ?? 'None...';

            echo "   Network: {$name}\n";
            echo "   - Management: {$management}\n";
            echo "   - VLAN: {$vlan}\n";
            echo "   - ID: {$networkId}\n";
            echo "\n";
        }

        // Use the first network for detailed example
        if (count($networks['data']) > 0) {
            $firstNetwork = $networks['data'][0];
            $networkId = $firstNetwork['id'];
            $networkName = $firstNetwork['name'] ?? 'Unnamed';

            // 2. Get detailed network information
            echo "2. Getting detailed information for: {$networkName}\n";
            $detailResponse = $apiClient->networks()->get($networkId);
            $details = $detailResponse->json();

            echo '   Enabled: '.(($details['enabled'] ?? false) ? 'Yes' : 'No')."\n";
            echo '   Gateway: '.($details['ipv4Configuration']['hostIpAddress'] ?? 'N/A')."\n";
            echo '   Internet Access Enabled: '.(($details['internetAccessEnabled'] ?? false) ? 'Yes' : 'No')."\n";
            echo '   dhcpConfiguration mode: '.($details['ipv4Configuration']['dhcpConfiguration']['mode'] ?? 'N/A')."\n";
            echo "\n";
        }
    } else {
        echo "   No networks found.\n\n";
    }// 3. Example: Create a new network (commented out for safety)
    echo "3. Creating a New Network (example - commented out)\n";
    echo "   According to the API spec, networks require a 'management' type:\n";
    echo "   - UNMANAGED: Simple VLAN-only network\n";
    echo "   - GATEWAY: Network managed by the gateway (with DHCP, routing, etc.)\n";
    echo "   - SWITCH: Network managed by switch\n\n";
    echo "   Example: Create a simple UNMANAGED network:\n";
    echo "   \$apiClient->networks()->create([\n";
    echo "       'management' => 'UNMANAGED',\n";
    echo "       'name' => 'VLAN 10',\n";
    echo "       'enabled' => true,\n";
    echo "       'vlanId' => 10\n";
    echo "   ]);\n\n";
    echo "   Example: Create a GATEWAY managed network (complex structure):\n";
    echo "   \$apiClient->networks()->create([\n";
    echo "       'management' => 'GATEWAY',\n";
    echo "       'name' => 'Guest Network',\n";
    echo "       'enabled' => true,\n";
    echo "       'vlanId' => 20,\n";
    echo "       'isolationEnabled' => true,\n";
    echo "       'cellularBackupEnabled' => false,\n";
    echo "       'internetAccessEnabled' => true,\n";
    echo "       'mdnsForwardingEnabled' => false,\n";
    echo "       'zoneId' => 'your-firewall-zone-uuid',\n";
    echo "       'ipv4Configuration' => [\n";
    echo "           'autoScaleEnabled' => false,\n";
    echo "           'hostIpAddress' => '192.168.20.1',\n";
    echo "           'prefixLength' => 24\n";
    echo "       ]\n";
    echo "   ]);\n\n"; // Uncomment to actually create a network:
    // $createResponse = $apiClient->networks()->create([
    //     'management' => 'UNMANAGED',
    //     'name' => 'Test VLAN',
    //     'enabled' => true,
    //     'vlanId' => 99
    // ]);
    // 4. Example: Update a network (commented out for safety)
    echo "4. Updating a Network (example - commented out)\n";
    echo "   Example code to enable/disable a network:\n";
    echo "   \$apiClient->networks()->update(\$networkId, [\n";
    echo "       'enabled' => false\n";
    echo "   ]);\n\n";
    echo "   Example code to update network name:\n";
    echo "   \$apiClient->networks()->update(\$networkId, [\n";
    echo "       'name' => 'Updated Network Name'\n";
    echo "   ]);\n\n"; // Uncomment to actually update a network:
    // if (isset($networkId)) {
    //     $updateResponse = $apiClient->networks()->update($networkId, [
    //         'enabled' => false
    //     ]);
    // }
    // 5. Example: Delete a network (commented out for safety)
    echo "5. Deleting a Network (example - commented out)\n";
    echo "   Example code to delete a network:\n";
    echo "   \$apiClient->networks()->delete(\$networkId);\n\n";
    echo "   To force delete (skip dependency checks):\n";
    echo "   \$apiClient->networks()->delete(\$networkId, force: true);\n\n"; // Uncomment to actually delete a network:
    // $apiClient->networks()->delete($networkId);
    // 6. Pagination example
    echo "6. Pagination Example:\n";
    echo "   Get first 10 networks:\n";
    echo "   \$apiClient->networks()->list(offset: 0, limit: 10);\n\n";

    $paginatedResponse = $apiClient->networks()->list(offset: 0, limit: 10);
    $paginated = $paginatedResponse->json();
    $count = isset($paginated['data']) ? count($paginated['data']) : 0;

    echo "   Retrieved {$count} networks\n\n"; // 7. Filtering examples
    echo "7. Filtering Examples (based on official API filterable properties):\n";
    echo "   Available filterable properties:\n";
    echo "   - management (STRING), id (UUID), name (STRING), enabled (BOOLEAN)\n";
    echo "   - vlanId (INTEGER), deviceId (UUID), metadata.origin (STRING)\n\n";
    echo "   Get networks by name pattern:\n";
    echo "   \$apiClient->networks()->list(filter: \"name.like('Guest%')\");\n\n";
    echo "   Get networks with specific VLAN ID:\n";
    echo "   \$apiClient->networks()->list(filter: 'vlanId.eq(10)');\n\n";
    echo "   Get only enabled networks:\n";
    echo "   \$apiClient->networks()->list(filter: 'enabled.eq(true)');\n\n";
    echo "   Get networks by VLAN range:\n";
    echo "   \$apiClient->networks()->list(filter: 'and(vlanId.ge(10), vlanId.le(20))');\n\n";
    echo str_repeat('=', 50)."\n";
    echo "Example completed successfully!\n";
} catch (Exception $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
