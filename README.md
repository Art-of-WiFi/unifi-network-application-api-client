# UniFi Network Application API Client (Saloon-based)

A modern PHP API client for the official UniFi Network Application API, built on [Saloon](https://docs.saloon.dev/) with a fluent interface for easy integration and powerful features.

This client provides a clean, intuitive way to interact with your UniFi Network Application, supporting all major operations including site management, device control, client monitoring, network configuration, WiFi management, and more.

## Features

- Built on Saloon v3 for robust HTTP communication
- Fluent interface with method chaining for elegant code
- Full support for the official UniFi Network Application API (v10.1.39+)
- Comprehensive coverage of all API endpoints
- Strongly typed using PHP 8.1+ features
- Easy to use for beginners, flexible for advanced users
- Well-documented with inline PHPDoc for IDE auto-completion
- PSR-4 autoloading

## Requirements

- PHP 8.1 or higher
- Composer
- A UniFi Network Application (Controller) with API key access
- Network access to your UniFi Controller

## Installation

Install via Composer:

```bash
composer require art-of-wifi/unifi-network-application-api-client
```

## Authentication & Prerequisites

### Generating an API Key

You **must** generate an API key from your UniFi Network Application to use this client:

1. Log into your UniFi Network Application
2. Navigate to **Settings** → **Integrations**
3. Click **Create API Key**
4. Give it a descriptive name and save the key securely
5. Use this key when initializing the client

### Important Notes

- API keys are site-specific and tied to your user account
- The account generating the API key must have appropriate permissions
- API keys can be revoked at any time from the Integrations page
- For local controllers with self-signed certificates, you may need to disable SSL verification (not recommended for production)

## Quick Start

Here's the simplest example to get you started:

```php
<?php

require_once 'vendor/autoload.php';

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

// Initialize the client
$apiClient = new UnifiClient(
    baseUrl: 'https://192.168.1.1',  // Your controller URL
    apiKey: 'your-api-key-here',      // Your generated API key
    verifySsl: false                   // Set to true for production with valid SSL
);

// Get all sites
$response = $apiClient->sites()->list();
$sites = $response->json();

// Display site names
foreach ($sites['data'] ?? [] as $site) {
    echo "Site: {$site['name']}\n";
}
```

That's it! You're now connected to your UniFi Network Application.

## Basic Usage

### Setting a Site Context

Most UniFi API operations require a site ID. You can set this once, and it will be used for all subsequent calls:

```php
<?php

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

$apiClient = new UnifiClient('https://192.168.1.1', 'your-api-key');

// Set the site ID (get this from the sites list)
$apiClient->setSiteId('550e8400-e29b-41d4-a716-446655440000');

// Now all operations use this site automatically
$devices = $apiClient->devices()->listAdopted();
$clients = $apiClient->clients()->list();
```

**Note:** The site ID is a UUID (not the short site name). You can retrieve site IDs using `$apiClient->sites()->list()`.

### Working with Devices

```php
<?php

// List all adopted devices
$response = $apiClient->devices()->listAdopted();
$devices = $response->json();

// Get a specific device by ID
$device = $apiClient->devices()->get('device-uuid-here');

// Get device statistics
$stats = $apiClient->devices()->getStatistics('device-uuid-here');

// Execute an action on a device (only RESTART is documented)
$apiClient->devices()->executeAction('device-uuid-here', [
    'action' => 'RESTART'
]);
```

### Managing Clients

```php
<?php

// List all connected clients
$response = $apiClient->clients()->list();
$clients = $response->json();

// Get details for a specific client
$apiClient->clients()->get('client-uuid-or-mac');

// Authorize a guest client
$apiClient->clients()->executeAction('client-uuid', [
    'action' => 'AUTHORIZE_GUEST_ACCESS',
    'timeLimitMinutes' => 60  // Grant access for 60 minutes
]);
```

### Network Management

```php
<?php

// List all networks
$networks = $apiClient->networks()->list();

// Create a new UNMANAGED network (simple VLAN)
$apiClient->networks()->create([
    'management' => 'UNMANAGED',
    'name' => 'Guest Network',
    'enabled' => true,
    'vlanId' => 10
]);

// Update a network
$apiClient->networks()->update('network-uuid', [
    'name' => 'Updated Guest Network'
]);

// Delete a network
$apiClient->networks()->delete('network-uuid');
```

### WiFi Management

```php
<?php

// List all WiFi broadcasts (SSIDs)
$wifiNetworks = $apiClient->wifiBroadcasts()->list();

// Create a new WiFi network (requires complex nested structure - see examples)
// Refer to examples/05-wifi-management.php for complete structure

// Update WiFi settings
$apiClient->wifiBroadcasts()->update('wifi-uuid', [
    'name' => 'Updated WiFi Name'
]);

// Delete a WiFi network
$apiClient->wifiBroadcasts()->delete('wifi-uuid');
```

### Hotspot Vouchers

```php
<?php

// Create vouchers for guest access
$apiClient->hotspot()->createVouchers([
    'count' => 10,
    'timeLimitMinutes' => 480,
    'authorizedGuestLimit' => 1  // How many guests can use same voucher
]);

// List all vouchers
$vouchers = $apiClient->hotspot()->listVouchers();

// Delete a voucher
$apiClient->hotspot()->deleteVoucher('voucher-uuid');
```

### Supporting Resources

Access reference data for configuration:

```php
<?php

// List available WAN interfaces
$wans = $apiClient->supportingResources()->listWanInterfaces();

// List DPI (Deep Packet Inspection) categories
$dpiCategories = $apiClient->supportingResources()->listDpiCategories();

// List DPI applications
$dpiApps = $apiClient->supportingResources()->listDpiApplications();

// List countries (for regulatory compliance)
$countries = $apiClient->supportingResources()->listCountries();

// List RADIUS profiles
$radiusProfiles = $apiClient->supportingResources()->listRadiusProfiles();

// List device tags
$deviceTags = $apiClient->supportingResources()->listDeviceTags();

// List site-to-site VPN tunnels
$vpnTunnels = $apiClient->supportingResources()->listSiteToSiteVpnTunnels();

// List VPN servers
$vpnServers = $apiClient->supportingResources()->listVpnServers();
```

### Firewall & ACL Management

```php
<?php

// List firewall zones
$zones = $apiClient->firewall()->listZones();

// Create a firewall zone
$apiClient->firewall()->createZone([
    'name' => 'DMZ',
    'networkIds' => []  // Array of network UUIDs
]);

// List ACL rules
$rules = $apiClient->aclRules()->list();

// Create an ACL rule (requires complex structure - see API docs)
$apiClient->aclRules()->create([
    'type' => 'IPV4',  // or 'MAC'
    'name' => 'Block Social Media',
    'enabled' => true,
    'action' => 'BLOCK',  // or 'ALLOW'
    'index' => 1000,
    // ... additional filters required
]);
```

## Advanced Usage

### Pagination

Many list endpoints support pagination:

```php
<?php

// Get page 2 with 50 results per page
$response = $apiClient->devices()->listAdopted(
    page: 2,
    limit: 50
);

$data = $response->json();
```

### Filtering

The UniFi API supports advanced filtering on many endpoints:

```php
<?php

// Filter devices by name
$response = $apiClient->devices()->listAdopted(
    filter: "name.like('AP-*')"
);

// Filter clients by type and access (wireless guests only)
$response = $apiClient->clients()->list(
    filter: 'and(type.eq("WIRELESS"), access.type.eq("GUEST"))'
);
```

#### Client Filterable Properties

According to the official API specification, the following properties are filterable for clients:

- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `WIRED`, `WIRELESS`, `VPN`, `TELEPORT`)
- `macAddress` (STRING) - `isNull`, `isNotNull`, `eq`, `ne`, `in`, `notIn`
- `ipAddress` (STRING) - `isNull`, `isNotNull`, `eq`, `ne`, `in`, `notIn`
- `connectedAt` (TIMESTAMP) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `access.type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `DEFAULT`, `GUEST`)
- `access.authorized` (BOOLEAN) - `isNull`, `isNotNull`, `eq`, `ne`

#### Device Filterable Properties

According to the official API specification, the following properties are filterable for adopted devices:

- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `macAddress` (STRING) - `eq`, `ne`, `in`, `notIn`
- `ipAddress` (STRING) - `eq`, `ne`, `in`, `notIn`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `model` (STRING) - `eq`, `ne`, `in`, `notIn`
- `state` (STRING) - `eq`, `ne`, `in`, `notIn`
- `supported` (BOOLEAN) - `eq`, `ne`
- `firmwareVersion` (STRING) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `like`, `in`, `notIn`
- `firmwareUpdatable` (BOOLEAN) - `eq`, `ne`
- `features` (SET(STRING)) - `isEmpty`, `contains`, `containsAny`, `containsAll`, `containsExactly`
- `interfaces` (SET(STRING)) - `isEmpty`, `contains`, `containsAny`, `containsAll`, `containsExactly`

#### Network Filterable Properties

According to the official API specification, the following properties are filterable for networks:

- `management` (STRING) - `eq`, `ne`, `in`, `notIn`
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `enabled` (BOOLEAN) - `eq`, `ne`
- `vlanId` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`
- `deviceId` (UUID) - `eq`, `ne`, `in`, `notIn`, `isNull`, `isNotNull`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`

For full filtering syntax documentation, see the [UniFi API Filtering Guide](https://developer.ui.com).

### Working with Responses

All methods return a Saloon `Response` object with helpful methods:

```php
<?php

$response = $apiClient->sites()->list();

// Get response as array
$data = $response->json();

// Get response as object
$data = $response->object();

// Check HTTP status
$status = $response->status();
$isSuccessful = $response->successful();

// Access headers
$contentType = $response->header('Content-Type');

// Get raw body
$body = $response->body();
```

### Error Handling

Handle API errors gracefully using try-catch blocks:

```php
<?php

use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\ServerException;

try {
    $response = $apiClient->devices()->get('invalid-uuid');
    $device = $response->json();
} catch (ClientException $e) {
    // 4xx errors (client errors like 404 Not Found)
    echo "Client error: " . $e->getMessage();
    echo "Status code: " . $e->getResponse()->status();
} catch (ServerException $e) {
    // 5xx errors (server errors)
    echo "Server error: " . $e->getMessage();
} catch (RequestException $e) {
    // General request errors
    echo "Request failed: " . $e->getMessage();
}
```

### SSL Certificate Verification

For production environments with valid SSL certificates, enable SSL verification:

```php
<?php

$apiClient = new UnifiClient(
    baseUrl: 'https://unifi.example.com',
    apiKey: 'your-api-key',
    verifySsl: true  // Verify SSL certificates
);
```

For local development with self-signed certificates, you can disable verification:

```php
<?php

$apiClient = new UnifiClient(
    baseUrl: 'https://192.168.1.1',
    apiKey: 'your-api-key',
    verifySsl: false  // Skip SSL verification (not recommended for production)
);
```

## Available Resources

The client provides access to the following resources:

| Resource | Description |
|----------|-------------|
| `applicationInfo()` | General application information and metadata |
| `sites()` | Site management and listing |
| `devices()` | Device management, monitoring, and actions |
| `clients()` | Connected client management and guest authorization |
| `networks()` | Network configuration (VLANs, DHCP, etc.) |
| `wifiBroadcasts()` | WiFi network (SSID) management |
| `hotspot()` | Guest voucher management |
| `firewall()` | Firewall zone configuration |
| `aclRules()` | Access Control List (ACL) rule management |
| `trafficMatchingLists()` | Port and IP address lists for firewall policies |
| `supportingResources()` | Reference data (WAN interfaces, DPI categories, countries, RADIUS profiles, device tags) |

## Examples

See the [`examples/`](examples/) directory for complete working examples:

- [Basic Usage](examples/01-basic-usage.php) - Getting started with the client
- [Device Management](examples/02-device-management.php) - Working with UniFi devices
- [Client Operations](examples/03-client-operations.php) - Managing connected clients
- [Network Configuration](examples/04-network-configuration.php) - Creating and managing networks
- [WiFi Management](examples/05-wifi-management.php) - WiFi broadcast configuration
- [Error Handling](examples/06-error-handling.php) - Proper exception handling

## Migrating from the Legacy Client

If you're migrating from the [legacy UniFi API client](https://github.com/Art-of-WiFi/UniFi-API-client), this new Saloon-based client offers:

- Modern PHP 8.1+ syntax with typed properties
- Fluent interface for more readable code
- Better error handling with exceptions
- Comprehensive IDE support through PHPDoc
- Based on the official API (not the legacy private API)

The main differences:

1. **Authentication:** Uses API keys instead of username/password login
2. **Return values:** Returns Saloon Response objects instead of arrays directly
3. **Method names:** More descriptive and consistent naming
4. **Site handling:** Explicit site ID setting with `setSiteId()`

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## Support

If you encounter any issues or have questions:

- Check the [examples directory](examples/) for working code samples
- Review the [official UniFi API documentation](https://developer.ui.com)
- Open an issue on [GitHub](https://github.com/Art-of-WiFi/unifi-network-application-api-client)

## Credits

This library is developed and maintained by [Art of WiFi](https://artofwifi.net) and is the successor to the [UniFi API client](https://github.com/Art-of-WiFi/UniFi-API-client).

Built with [Saloon](https://docs.saloon.dev/) by Sammyjo20.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
