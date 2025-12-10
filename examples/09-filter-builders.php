<?php
/**
 * Example 09: Using Filter Builders
 *
 * This example demonstrates how to use the type-safe filter builders
 * for more readable and maintainable filtering.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources\CountriesFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Devices\DeviceFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Clients\ClientFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientType;
use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientAccessType;

$config = require_once __DIR__ . '/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$siteId        = $config['site_id'];
$verifySsl     = $config['verify_ssl'];

// Initialize the API client
$apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);

echo "=== Filter Builders Example ===\n\n";

// ============================================================================
// 1. COUNTRIES FILTER - Simple Example
// ============================================================================
echo "1. Countries Filter Examples:\n";
echo str_repeat('-', 50) . "\n\n";

try {
    // Example 1a: Find United States
    echo "Finding United States:\n";
    $response = $apiClient->supportingResources()->listCountries(
        filter: CountriesFilter::unitedStates()
    );
    $countries = $response->json();

    foreach ($countries['data'] ?? [] as $country) {
        echo "  - {$country['name']} ({$country['code']})\n";
    }
    echo "\n";

    // Example 1b: Find countries with "Kingdom" in the name
    echo "Finding countries with 'Kingdom' in the name:\n";
    $response = $apiClient->supportingResources()->listCountries(
        filter: CountriesFilter::name()->like('*Kingdom*')
    );
    $countries = $response->json();

    foreach ($countries['data'] ?? [] as $country) {
        echo "  - {$country['name']} ({$country['code']})\n";
    }
    echo "\n";

    // Example 1c: Find multiple countries by code
    echo "Finding US, GB, CA, AU:\n";
    $response = $apiClient->supportingResources()->listCountries(
        filter: CountriesFilter::code()->in(['US', 'GB', 'CA', 'AU'])
    );
    $countries = $response->json();

    foreach ($countries['data'] ?? [] as $country) {
        echo "  - {$country['name']} ({$country['code']})\n";
    }
    echo "\n";

    // Example 1d: Find North American countries using preset
    echo "Finding North American countries:\n";
    $response = $apiClient->supportingResources()->listCountries(
        filter: CountriesFilter::northAmerica()
    );
    $countries = $response->json();

    foreach ($countries['data'] ?? [] as $country) {
        echo "  - {$country['name']} ({$country['code']})\n";
    }
    echo "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// 2. DEVICE FILTER - If you have a site set up
// ============================================================================
// Uncomment and set your site ID to test device filters
/*
$apiClient->setSiteId('your-site-id-here');

echo "2. Device Filter Examples:\n";
echo str_repeat('-', 50) . "\n\n";

try {
    // Example 2a: Find all access points
    echo "Finding all access points:\n";
    $response = $apiClient->devices()->listAdopted(
        filter: DeviceFilter::accessPoints()
    );
    $devices = $response->json();

    foreach ($devices['data'] ?? [] as $device) {
        echo "  - {$device['name']} ({$device['model']})\n";
    }
    echo "\n";

    // Example 2b: Find devices by name pattern
    echo "Finding devices starting with 'AP-':\n";
    $response = $apiClient->devices()->listAdopted(
        filter: DeviceFilter::name()->like('AP-*')
    );
    $devices = $response->json();

    foreach ($devices['data'] ?? [] as $device) {
        echo "  - {$device['name']}\n";
    }
    echo "\n";

    // Example 2c: Find devices by specific models
    echo "Finding U6-LR and U6-PRO devices:\n";
    $response = $apiClient->devices()->listAdopted(
        filter: DeviceFilter::model()->in(['U6-LR', 'U6-PRO'])
    );
    $devices = $response->json();

    foreach ($devices['data'] ?? [] as $device) {
        echo "  - {$device['name']} ({$device['model']})\n";
    }
    echo "\n";

    // Example 2d: Find devices needing firmware updates
    echo "Finding devices needing firmware updates:\n";
    $response = $apiClient->devices()->listAdopted(
        filter: DeviceFilter::needsFirmwareUpdate()
    );
    $devices = $response->json();

    foreach ($devices['data'] ?? [] as $device) {
        echo "  - {$device['name']} - Current: {$device['firmwareVersion']}\n";
    }
    echo "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}
*/

// ============================================================================
// 3. CLIENT FILTER - If you have clients connected
// ============================================================================
// Uncomment and set your site ID to test client filters
/*
$apiClient->setSiteId('your-site-id-here');

echo "3. Client Filter Examples:\n";
echo str_repeat('-', 50) . "\n\n";

try {
    // Example 3a: Find wireless clients
    echo "Finding wireless clients:\n";
    $response = $apiClient->clients()->list(
        filter: ClientFilter::wireless()
    );
    $clients = $response->json();

    echo "  Found " . count($clients['data'] ?? []) . " wireless clients\n\n";

    // Example 3b: Find guest clients
    echo "Finding guest clients:\n";
    $response = $apiClient->clients()->list(
        filter: ClientFilter::guests()
    );
    $clients = $response->json();

    echo "  Found " . count($clients['data'] ?? []) . " guest clients\n\n";

    // Example 3c: Find wireless guests using complex filter
    echo "Finding wireless guest clients (using complex filter):\n";
    $response = $apiClient->clients()->list(
        filter: ClientFilter::and(
            ClientFilter::type()->equals(ClientType::WIRELESS),
            ClientFilter::accessType()->equals(ClientAccessType::GUEST)
        )
    );
    $clients = $response->json();

    echo "  Found " . count($clients['data'] ?? []) . " wireless guest clients\n\n";

    // Example 3d: Find wireless guests using preset
    echo "Finding wireless guest clients (using preset):\n";
    $response = $apiClient->clients()->list(
        filter: ClientFilter::wirelessGuests()
    );
    $clients = $response->json();

    echo "  Found " . count($clients['data'] ?? []) . " wireless guest clients\n\n";

    // Example 3e: Find clients with IP addresses
    echo "Finding clients with assigned IP addresses:\n";
    $response = $apiClient->clients()->list(
        filter: ClientFilter::withIpAddress()
    );
    $clients = $response->json();

    foreach ($clients['data'] ?? [] as $client) {
        echo "  - {$client['ipAddress']} ({$client['type']})\n";
    }
    echo "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}
*/

// ============================================================================
// 4. COMPARISON: Raw Strings vs Filter Builders
// ============================================================================
echo "4. Comparison: Raw Strings vs Filter Builders:\n";
echo str_repeat('-', 50) . "\n\n";

echo "Old way (raw strings):\n";
echo "  \$client->supportingResources()->listCountries(\n";
echo "      filter: \"code.in('US', 'GB', 'CA')\"\n";
echo "  )\n\n";

echo "New way (filter builders):\n";
echo "  \$client->supportingResources()->listCountries(\n";
echo "      filter: CountriesFilter::code()->in(['US', 'GB', 'CA'])\n";
echo "  )\n\n";

echo "Benefits:\n";
echo "  ✓ IDE autocomplete\n";
echo "  ✓ Type safety\n";
echo "  ✓ No string escaping issues\n";
echo "  ✓ Compile-time error checking\n";
echo "  ✓ Better readability\n";
echo "  ✓ Preset filters for common cases\n\n";

echo "Example completed successfully!\n";
