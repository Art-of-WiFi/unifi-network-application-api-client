<?php
/**
 * Example 7: Supporting Resources
 *
 * This example demonstrates how to access reference data endpoints such as
 * WAN interfaces, DPI categories, countries, RADIUS profiles, and device tags.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$config = require_once __DIR__ . '/config.php';

// Configuration - Update the values in the config.php file
$controllerUrl = $config['base_url'];
$apiKey        = $config['api_key'];
$siteId        = $config['site_id'];
$verifySsl     = $config['verify_ssl'];

// Initialize the API client
$apiClient = new UnifiClient($controllerUrl, $apiKey, $verifySsl);

echo "UniFi API Client - Supporting Resources Example\n";
echo str_repeat('=', 50) . "\n\n";

// Global resources (no site ID required)
echo "GLOBAL REFERENCE DATA\n";
echo str_repeat('-', 50) . "\n\n";

// 1. List DPI Categories
echo "1. DPI (Deep Packet Inspection) Categories:\n";
try {
    $categoriesResponse = $apiClient->supportingResources()->listDpiCategories(limit: 10);
    $categories         = $categoriesResponse->json();
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

if (isset($categories['data']) && count($categories['data']) > 0) {
    echo "   Total categories: " . ($categories['totalCount'] ?? count($categories['data'])) . "\n";
    echo "   First 10 categories:\n";
    foreach (array_slice($categories['data'], 0, 10) as $category) {
        $name = $category['name'] ?? 'Unknown';
        $id   = $category['id'] ?? 'N/A';
        echo "   - {$name} (ID: {$id})\n";
    }
} else {
    echo "   No DPI categories found.\n";
}
echo "\n";

// 2. List DPI Applications
echo "2. DPI Applications (sample):\n";
try {
    $appsResponse = $apiClient->supportingResources()->listDpiApplications(limit: 5);
    $apps         = $appsResponse->json();
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

if (isset($apps['data']) && count($apps['data']) > 0) {
    echo "   Total applications: " . ($apps['totalCount'] ?? count($apps['data'])) . "\n";
    echo "   Sample applications:\n";
    foreach (array_slice($apps['data'], 0, 5) as $app) {
        $name     = $app['name'] ?? 'Unknown';
        $category = $app['category'] ?? 'N/A';
        echo "   - {$name} (Category: {$category})\n";
    }
} else {
    echo "   No DPI applications found.\n";
}
echo "\n";

// 3. List Countries
echo "3. Countries (ISO codes, sample):\n";
try {
    $countriesResponse = $apiClient->supportingResources()->listCountries(limit: 10);
    $countries         = $countriesResponse->json();
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

if (isset($countries['data']) && count($countries['data']) > 0) {
    echo "   Total countries: " . ($countries['totalCount'] ?? count($countries['data'])) . "\n";
    echo "   Sample countries:\n";
    foreach (array_slice($countries['data'], 0, 10) as $country) {
        $name = $country['name'] ?? 'Unknown';
        $code = $country['code'] ?? 'N/A';
        echo "   - {$name} ({$code})\n";
    }
} else {
    echo "   No countries found.\n";
}
echo "\n";

// Site-specific resources (require site ID)
echo "\nSITE-SPECIFIC REFERENCE DATA\n";
echo str_repeat('-', 50) . "\n\n";

// Set the site context
$apiClient->setSiteId($siteId);

// 4. List WAN Interfaces
echo "4. WAN Interfaces:\n";
try {
    $wansResponse = $apiClient->supportingResources()->listWanInterfaces();
    $wans         = $wansResponse->json();

    if (isset($wans['data']) && count($wans['data']) > 0) {
        foreach ($wans['data'] as $wan) {
            $name = $wan['name'] ?? 'Unknown';
            $id   = $wan['id'] ?? 'N/A';
            $type = $wan['type'] ?? 'N/A';
            echo "   - {$name} (ID: {$id}, Type: {$type})\n";
        }
    } else {
        echo "   No WAN interfaces found.\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. List RADIUS Profiles
echo "5. RADIUS Profiles:\n";
try {
    $radiusResponse = $apiClient->supportingResources()->listRadiusProfiles();
    $radiusProfiles = $radiusResponse->json();

    if (isset($radiusProfiles['data']) && count($radiusProfiles['data']) > 0) {
        foreach ($radiusProfiles['data'] as $profile) {
            $name = $profile['name'] ?? 'Unknown';
            $id   = $profile['id'] ?? 'N/A';
            echo "   - {$name} (ID: {$id})\n";
        }
    } else {
        echo "   No RADIUS profiles found.\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. List Device Tags
echo "6. Device Tags:\n";
try {
    $tagsResponse = $apiClient->supportingResources()->listDeviceTags();
    $tags         = $tagsResponse->json();

    if (isset($tags['data']) && count($tags['data']) > 0) {
        foreach ($tags['data'] as $tag) {
            $name  = $tag['name'] ?? 'Unknown';
            $id    = $tag['id'] ?? 'N/A';
            $color = $tag['color'] ?? 'N/A';
            echo "   - {$name} (ID: {$id}, Color: {$color})\n";
        }
    } else {
        echo "   No device tags found.\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 7. List Site-to-Site VPN Tunnels
echo "7. Site-to-Site VPN Tunnels:\n";
try {
    $tunnelsResponse = $apiClient->supportingResources()->listSiteToSiteVpnTunnels();
    $tunnels         = $tunnelsResponse->json();

    if (isset($tunnels['data']) && count($tunnels['data']) > 0) {
        foreach ($tunnels['data'] as $tunnel) {
            $name = $tunnel['name'] ?? 'Unknown';
            $type = $tunnel['type'] ?? 'N/A';
            echo "   - {$name} (Type: {$type})\n";
        }
    } else {
        echo "   No VPN tunnels found.\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 8. List VPN Servers
echo "8. VPN Servers:\n";
try {
    $serversResponse = $apiClient->supportingResources()->listVpnServers();
    $servers         = $serversResponse->json();

    if (isset($servers['data']) && count($servers['data']) > 0) {
        foreach ($servers['data'] as $server) {
            $name    = $server['name'] ?? 'Unknown';
            $type    = $server['type'] ?? 'N/A';
            $enabled = ($server['enabled'] ?? false) ? 'Enabled' : 'Disabled';
            echo "   - {$name} (Type: {$type}, Status: {$enabled})\n";
        }
    } else {
        echo "   No VPN servers found.\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 9. Filtering examples
echo "9. Filtering Examples (based on official API filterable properties):\n";

echo "   Countries filterable properties: code (STRING), name (STRING)\n";
echo "   Filter countries by name:\n";
echo "   \$apiClient->supportingResources()->listCountries(filter: \"name.like('United*')\");\n\n";

echo "   Filter countries by code:\n";
echo "   \$apiClient->supportingResources()->listCountries(filter: 'code.eq(\"US\")');\n\n";

echo "   DPI Applications filterable properties: id (INTEGER), name (STRING)\n";
echo "   Filter DPI applications by name:\n";
echo "   \$apiClient->supportingResources()->listDpiApplications(filter: \"name.like('Facebook%')\");\n\n";

echo "   DPI Categories filterable properties: id (INTEGER), name (STRING)\n";
echo "   Filter DPI categories by name:\n";
echo "   \$apiClient->supportingResources()->listDpiCategories(filter: \"name.like('Social%')\");\n\n";

echo str_repeat('=', 50) . "\n";
echo "Example completed successfully!\n\n";

echo "Note: Supporting Resources are read-only reference data endpoints.\n";
echo "They provide essential configuration data like:\n";
echo "- DPI categories and applications for traffic rules\n";
echo "- Country codes for regulatory compliance\n";
echo "- WAN interfaces for network configuration\n";
echo "- RADIUS profiles for authentication\n";
echo "- Device tags for organization\n";
echo "- VPN configurations\n";
