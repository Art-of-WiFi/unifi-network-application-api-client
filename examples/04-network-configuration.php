<?php
/**
 * Example 4: Network Configuration
 *
 * This example demonstrates how to manage networks including
 * listing, creating, updating, and deleting network configurations.
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

echo "UniFi API Client - Network Configuration Example\n";
echo str_repeat('=', 50) . "\n\n";

// 1. List all networks
echo "1. Listing all networks...\n";
$networksResponse = $apiClient->networks()->list();
$networks = $networksResponse->json();

if (isset($networks['data']) && count($networks['data']) > 0) {
    foreach ($networks['data'] as $network) {
        $name = $network['name'] ?? 'Unnamed Network';
        $purpose = $network['purpose'] ?? 'Unknown';
        $vlan = $network['vlan'] ?? 'None';
        $subnet = $network['subnet'] ?? 'No subnet';

        echo "   Network: {$name}\n";
        echo "   - Purpose: {$purpose}\n";
        echo "   - VLAN: {$vlan}\n";
        echo "   - Subnet: {$subnet}\n";
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

        if (isset($details['data'])) {
            $data = $details['data'];
            echo "   DHCP Enabled: " . (($data['dhcp_enabled'] ?? false) ? 'Yes' : 'No') . "\n";
            echo "   Gateway: " . ($data['gateway_ip'] ?? 'N/A') . "\n";
            echo "   DNS Servers: " . implode(', ', $data['dns_servers'] ?? []) . "\n";
        }
        echo "\n";
    }
} else {
    echo "   No networks found.\n\n";
}

// 3. Example: Create a new network (commented out for safety)
echo "3. Creating a New Network (example - commented out)\n";
echo "   Example code to create a guest network:\n";
echo "   \$apiClient->networks()->create([\n";
echo "       'name' => 'Guest Network',\n";
echo "       'purpose' => 'guest',\n";
echo "       'vlan' => 10,\n";
echo "       'subnet' => '192.168.10.0/24',\n";
echo "       'dhcp_enabled' => true,\n";
echo "       'dhcp_start' => '192.168.10.100',\n";
echo "       'dhcp_end' => '192.168.10.200',\n";
echo "       'dhcp_lease_time' => 86400,  // 24 hours\n";
echo "       'gateway_ip' => '192.168.10.1',\n";
echo "       'dns_servers' => ['8.8.8.8', '8.8.4.4']\n";
echo "   ]);\n\n";

// Uncomment to actually create a network:
// $createResponse = $apiClient->networks()->create([
//     'name' => 'API Test Network',
//     'purpose' => 'corporate',
//     'vlan' => 99,
//     'subnet' => '192.168.99.0/24',
//     'dhcp_enabled' => true,
//     'gateway_ip' => '192.168.99.1'
// ]);

// 4. Example: Update a network (commented out for safety)
echo "4. Updating a Network (example - commented out)\n";
echo "   Example code to update a network's DNS servers:\n";
echo "   \$apiClient->networks()->update(\$networkId, [\n";
echo "       'dns_servers' => ['1.1.1.1', '1.0.0.1']\n";
echo "   ]);\n\n";

// Uncomment to actually update a network:
// if (isset($networkId)) {
//     $updateResponse = $apiClient->networks()->update($networkId, [
//         'dns_servers' => ['1.1.1.1', '1.0.0.1']
//     ]);
// }

// 5. Example: Delete a network (commented out for safety)
echo "5. Deleting a Network (example - commented out)\n";
echo "   Example code to delete a network:\n";
echo "   \$apiClient->networks()->delete(\$networkId);\n\n";
echo "   To force delete (skip dependency checks):\n";
echo "   \$apiClient->networks()->delete(\$networkId, force: true);\n\n";

// Uncomment to actually delete a network:
// $apiClient->networks()->delete($networkId);

// 6. Pagination example
echo "6. Pagination Example:\n";
echo "   Get first page with 10 networks:\n";
echo "   \$apiClient->networks()->list(page: 1, limit: 10);\n\n";

$paginatedResponse = $apiClient->networks()->list(page: 1, limit: 10);
$paginated = $paginatedResponse->json();
$count = isset($paginated['data']) ? count($paginated['data']) : 0;
echo "   Retrieved {$count} networks\n\n";

// 7. Filtering example
echo "7. Filtering Example:\n";
echo "   Get only guest networks:\n";
echo "   \$apiClient->networks()->list(filter: \"purpose.eq('guest')\");\n\n";

echo "   Get networks with specific VLAN:\n";
echo "   \$apiClient->networks()->list(filter: 'vlan.eq(10)');\n\n";

echo str_repeat('=', 50) . "\n";
echo "Example completed successfully!\n";
