# UniFi Network Application API Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/art-of-wifi/unifi-network-application-api-client.svg?style=flat-square)](https://packagist.org/packages/art-of-wifi/unifi-network-application-api-client)
[![PHP Version](https://img.shields.io/packagist/php-v/art-of-wifi/unifi-network-application-api-client.svg?style=flat-square)](https://packagist.org/packages/art-of-wifi/unifi-network-application-api-client)
[![License](https://img.shields.io/packagist/l/art-of-wifi/unifi-network-application-api-client.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/art-of-wifi/unifi-network-application-api-client.svg?style=flat-square)](https://packagist.org/packages/art-of-wifi/unifi-network-application-api-client)

A modern PHP API client for the official UniFi Network Application API, built on [Saloon](https://docs.saloon.dev/) with a fluent interface
for easy integration and powerful features.

This client provides a clean, intuitive way to interact with your UniFi Network Application, supporting all major
operations including site management, device control, client monitoring, network configuration, WiFi management, and
more.

It is not a direct successor to the [UniFi API client](https://github.com/Art-of-WiFi/UniFi-API-client) which has been
developed for the legacy, "unofficial" UniFi API. At this point in time, the "unofficial" API supports more endpoints
than the official API. If your integration requirements can be met with the official API, we recommend using this
client. For a richer set of features, we currently recommend using the [legacy UniFi API client](https://github.com/Art-of-WiFi/UniFi-API-client).


## Features

- Built on Saloon v3 for robust HTTP communication
- Fluent interface with method chaining for elegant code
- Full support for the official UniFi Network Application API (v10.1.84+)
- Comprehensive coverage of all API endpoints
- Strongly typed using PHP 8.1+ features
- Easy to use for beginners, flexible for advanced users
- Well-documented with inline PHPDoc for IDE auto-completion
- PSR-4 autoloading
- Sends a `User-Agent` header with every request for easy troubleshooting


## Requirements

- PHP 8.1 or higher
- Composer
- A UniFi OS Server or UniFi OS console with API key access to the Network Application
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
2. Navigate to **Settings** → **Integrations** or straight to **Integrations** from the sidebar with the latest versions of the UI
3. Click **Create New API Key**
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

// Adopt a pending device by MAC address
$apiClient->devices()->adopt('00:11:22:33:44:55');

// Adopt a device, ignoring the device limit
$apiClient->devices()->adopt('00:11:22:33:44:55', ignoreDeviceLimit: true);

// Remove (unadopt) a device
$apiClient->devices()->remove('device-uuid-here');
```

### Managing Clients

```php
<?php

// List all connected clients
$response = $apiClient->clients()->list();
$clients = $response->json();

// Get details for a specific client
$apiClient->clients()->get('client-uuid-here');

// Authorize a guest client
// Requires a lookup for the client's MAC address using $apiClient->clients()->list() with an appropriate filter first.
// Until the Official API supports client device creation, this approach does imply you cannot pre-authorize guests using
// the API because they need to be connected to the network first.
$apiClient->clients()->executeAction('client-uuid-here', [
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
$apiClient->networks()->update('network-uuid-here', [
    'name' => 'Updated Guest Network'
]);

// Delete a network
$apiClient->networks()->delete('network-uuid-here');
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
$apiClient->wifiBroadcasts()->delete('wifi-uuid-here');
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
$apiClient->hotspot()->deleteVoucher('voucher-uuid-here');
```

### Supporting Resources

Access a variety of resources for the UniFi Network Application configuration:

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

// List firewall policies
$policies = $apiClient->firewall()->listPolicies();

// Create a firewall policy
$apiClient->firewall()->createPolicy([
    'name' => 'Block IoT to LAN',
    'enabled' => true,
    'action' => 'BLOCK',
    'source' => ['zoneId' => 'source-zone-uuid'],
    'destination' => ['zoneId' => 'destination-zone-uuid'],
]);

// Partially update a firewall policy (PATCH - only send changed fields)
$apiClient->firewall()->patchPolicy('policy-uuid', [
    'enabled' => false
]);

// Get/update firewall policy ordering between two zones
$ordering = $apiClient->firewall()->getPolicyOrdering(
    sourceFirewallZoneId: 'source-zone-uuid',
    destinationFirewallZoneId: 'destination-zone-uuid'
);
$apiClient->firewall()->updatePolicyOrdering(
    sourceFirewallZoneId: 'source-zone-uuid',
    destinationFirewallZoneId: 'destination-zone-uuid',
    data: ['orderedFirewallPolicyIds' => ['policy-1-uuid', 'policy-2-uuid']]
);

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

// Get/update ACL rule ordering
$ordering = $apiClient->aclRules()->getOrdering();
$apiClient->aclRules()->updateOrdering([
    'orderedAclRuleIds' => ['rule-1-uuid', 'rule-2-uuid', 'rule-3-uuid']
]);
```

### DNS Policies

```php
<?php

// List all DNS policies
$policies = $apiClient->dnsPolicies()->list();

// Create a DNS A record
$apiClient->dnsPolicies()->create([
    'type' => 'A',
    'enabled' => true,
    'domain' => 'myapp.local',
    'ipv4Address' => '192.168.1.100',
    'ttlSeconds' => 3600,
]);

// Create a DNS CNAME record
$apiClient->dnsPolicies()->create([
    'type' => 'CNAME',
    'enabled' => true,
    'domain' => 'alias.local',
    'targetDomain' => 'myapp.local',
    'ttlSeconds' => 3600,
]);

// Update a DNS policy
$apiClient->dnsPolicies()->update('dns-policy-uuid', [
    'enabled' => false,
    'ipv4Address' => '192.168.1.200',
]);

// Delete a DNS policy
$apiClient->dnsPolicies()->delete('dns-policy-uuid');
```


## Advanced Usage

### Pagination

All list endpoints use offset-based pagination:

```php
<?php

// Example: List adopted devices with pagination
$response = $apiClient->devices()->listAdopted(
    offset: 100,  // Skip first 100 results
    limit: 50     // Get 50 results
);

$data = $response->json();

// Response structure for list endpoints:
// {
//   "offset": 100,
//   "limit": 50,
//   "count": 50,        // Number of items in current response
//   "totalCount": 1000, // Total items available
//   "data": [...]       // Array of results
// }
```

The `offset` parameter specifies how many results to skip, while `limit` specifies the maximum number of results to return. All paginated endpoints follow this pattern.

### Filtering

The UniFi API supports advanced filtering on many endpoints. You can use either raw filter strings or the type-safe filter builders.

#### Filter Builders (Recommended)

For type-safe, IDE-friendly filtering with autocomplete, use the fluent filter builders:

```php
<?php

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Devices\DeviceFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Clients\ClientFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientType;
use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientAccessType;

// Simple filtering - find access points
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::name()->like('AP-*')
);

// Find devices by model
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::model()->in(['U6-LR', 'U6-PRO', 'U6-ENTERPRISE'])
);

// Complex filtering with AND - wireless guest clients
$clients = $apiClient->clients()->list(
    filter: ClientFilter::and(
        ClientFilter::type()->equals(ClientType::WIRELESS),
        ClientFilter::accessType()->equals(ClientAccessType::GUEST)
    )
);

// Complex filtering with OR - APs or Switches
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::or(
        DeviceFilter::model()->like('U6*'),
        DeviceFilter::model()->like('USW*')
    )
);

// Multiple conditions - devices needing updates
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::and(
        DeviceFilter::firmwareUpdatable()->equals(true),
        DeviceFilter::supported()->equals(true)
    )
);

// Null checks - clients with IP addresses
$clients = $apiClient->clients()->list(
    filter: ClientFilter::ipAddress()->isNotNull()
);

// Set operations - devices with WiFi 6
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::features()->contains('wifi6')
);

// Preset filters for common use cases
$aps = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::accessPoints()
);

$wirelessGuests = $apiClient->clients()->list(
    filter: ClientFilter::wirelessGuests()
);
```

**Countries Filter (Easy to Test!):**

The Countries endpoint is perfect for testing filters as it works without needing a site ID and has lots of data:

```php
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources\CountriesFilter;

// Find United States
$countries = $apiClient->supportingResources()->listCountries(
    filter: CountriesFilter::unitedStates()
);

// Find countries with "Kingdom" in the name
$countries = $apiClient->supportingResources()->listCountries(
    filter: CountriesFilter::name()->like('*Kingdom*')
);

// Find multiple specific countries
$countries = $apiClient->supportingResources()->listCountries(
    filter: CountriesFilter::code()->in(['US', 'GB', 'CA', 'AU'])
);

// Find North American countries
$countries = $apiClient->supportingResources()->listCountries(
    filter: CountriesFilter::northAmerica()
);
```

**Available Filter Classes:**
- `DeviceFilter` - For device filtering
- `ClientFilter` - For client filtering
- `NetworkFilter` - For network filtering
- `SitesFilter` - For site filtering
- `FirewallPolicyFilter` - For firewall policy filtering
- `DnsPolicyFilter` - For DNS policy filtering (with presets for record types)
- `CountriesFilter` - For country filtering (easy to test!)
- `DpiCategoriesFilter` - For DPI category filtering
- `DpiApplicationsFilter` - For DPI application filtering
- `SiteToSiteVpnTunnelsFilter` - For VPN tunnel filtering
- `VpnServersFilter` - For VPN server filtering
- `RadiusProfilesFilter` - For RADIUS profile filtering
- `DeviceTagsFilter` - For device tag filtering

**Available Enums:**
- `ClientType` - WIRED, WIRELESS, VPN, TELEPORT
- `ClientAccessType` - DEFAULT, GUEST

**Benefits of Filter Builders:**
- Full IDE autocomplete support
- Type safety - catches errors at development time
- Better readability for complex filters
- Property-specific methods for easy discovery
- Preset filters for common use cases
- Automatic value escaping and formatting

#### Raw Filter Strings

If preferred, you can also use raw filter strings from the Network Application API documentation in your controller:

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

#### Firewall Policy Filterable Properties

According to the official API specification, the following properties are filterable for firewall policies:

- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `source.zoneId` (UUID) - `eq`, `ne`, `in`, `notIn`
- `destination.zoneId` (UUID) - `eq`, `ne`, `in`, `notIn`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`

#### DNS Policy Filterable Properties

According to the official API specification, the following properties are filterable for DNS policies:

- `type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `A`, `AAAA`, `CNAME`, `MX`, `TXT`, `SRV`, `FORWARD_DOMAIN`)
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `enabled` (BOOLEAN) - `eq`, `ne`
- `domain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `ipv4Address` (STRING) - `eq`, `ne`, `in`, `notIn`
- `ipv6Address` (STRING) - `eq`, `ne`, `in`, `notIn`
- `targetDomain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `mailServerDomain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `text` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `serverDomain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `ipAddress` (STRING) - `eq`, `ne`, `in`, `notIn`
- `ttlSeconds` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `priority` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `service` (STRING) - `eq`, `ne`, `in`, `notIn`
- `protocol` (STRING) - `eq`, `ne`, `in`, `notIn`
- `port` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `weight` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`

For full filtering syntax documentation, see the Network Application API documentation in your controller.

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

### Response Structure: List vs Single Item

**IMPORTANT:** The UniFi API returns different response structures depending on the endpoint type:

**List Endpoints** (e.g., `list()`, `listAdopted()`, `listVouchers()`) return paginated data:
```php
<?php

// List endpoints return data in a 'data' array with pagination metadata
$response = $apiClient->devices()->listAdopted();
$result = $response->json();

// Access the array of items
$devices = $result['data'];  // Array of devices

// Pagination metadata is also available
$total = $result['total'] ?? null;
$offset = $result['offset'] ?? null;
$limit = $result['limit'] ?? null;

// Iterate through items
foreach ($result['data'] as $device) {
    echo $device['name'];
}
```

**Single Item Endpoints** (e.g., `get($uuid)`, `getVoucher($uuid)`) return the object directly:
```php
<?php

// Single item endpoints return the object WITHOUT a 'data' wrapper
$response = $apiClient->devices()->get($deviceId);
$device = $response->json();

// Access properties directly (NO 'data' key!)
echo $device['name'];           // ✓ Correct
echo $device['ipAddress'];      // ✓ Correct

// NOT like this:
// echo $device['data']['name']; // ✗ Wrong! Will cause errors
```

**Key Differences:**
- **List endpoints:** Use `$result['data']` to access items + pagination metadata
- **Single item endpoints:** Access properties directly, no `data` wrapper, no pagination metadata

**Quick Reference:**

| Method Type | Response Structure | Example Access |
|-------------|-------------------|----------------|
| `list()`, `listAdopted()`, etc. | `{ "data": [...], "total": X, ... }` | `$response->json()['data'][0]` |
| `get($id)`, `getVoucher($id)`, etc. | `{ "id": "...", "name": "...", ... }` | `$response->json()['name']` |

### Error Handling

Handle API errors gracefully using try-catch blocks:

```php
<?php

use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\ServerException;

try {
    $response = $apiClient->devices()->get('invalid-uuid-here');
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

### Version Information

You can retrieve the client library version at runtime, which is useful for troubleshooting and logging:

```php
<?php

echo $apiClient->getVersion(); // e.g., "1.0.0"
```

The version is also sent with every API request as a `User-Agent` header (`unifi-api-client-php/1.0.0`), which can help
when debugging API issues in controller logs. The version constant is also available directly
via `UnifiConnector::VERSION`.


## Available Resources

The client provides access to the following resources:

| Resource | Description |
|----------|-------------|
| `applicationInfo()` | General application information and metadata |
| `sites()` | Site management and listing |
| `devices()` | Device management, monitoring, actions, adoption, and removal |
| `clients()` | Connected client management and guest authorization |
| `networks()` | Network configuration (VLANs, DHCP, etc.) |
| `wifiBroadcasts()` | WiFi network (SSID) management |
| `hotspot()` | Guest voucher management |
| `firewall()` | Firewall zone and policy management, including policy ordering |
| `aclRules()` | Access Control List (ACL) rule management, including rule ordering |
| `dnsPolicies()` | DNS policy management (A, AAAA, CNAME, MX, TXT, SRV, forward domains) |
| `trafficMatchingLists()` | Port and IP address lists for firewall policies |
| `supportingResources()` | Reference data (WAN interfaces, DPI categories, countries, RADIUS profiles, device tags) |


## Examples

See the [`examples/`](examples/) directory for complete working examples:

- [Basic Usage](examples/01-basic-usage.php) - Getting started with the client
- [Device Management](examples/02-device-management.php) - Working with UniFi devices (including adopt/remove)
- [Client Operations](examples/03-client-operations.php) - Managing connected clients
- [Network Configuration](examples/04-network-configuration.php) - Creating and managing networks
- [WiFi Management](examples/05-wifi-management.php) - WiFi broadcast configuration
- [Error Handling](examples/06-error-handling.php) - Proper exception handling
- [Firewall Policies & DNS Policies](examples/10-firewall-dns-policies.php) - Firewall policies, ACL ordering, and DNS policies

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
- Review the official UniFi API documentation within your controller
- Open an issue on [GitHub](https://github.com/Art-of-WiFi/unifi-network-application-api-client)


## Credits

This library is developed and maintained by [Art of WiFi](https://artofwifi.net) and is developed for the official UniFi Network Application API.

Built with [Saloon](https://docs.saloon.dev/) by Sammyjo20.


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
