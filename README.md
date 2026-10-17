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

- Built on Saloon v4 for robust HTTP communication
- Fluent interface with method chaining for elegant code
- Full support for the official UniFi Network Application API (v10.1.84+, verified against v10.6.106 and v11.0.89)
- Comprehensive coverage of all API endpoints
- Connects directly to a console or remotely through the Site Manager connector at `api.ui.com`
- Strongly typed using PHP 8.2+ features
- Easy to use for beginners, flexible for advanced users
- Well-documented with inline PHPDoc for IDE auto-completion
- PSR-4 autoloading
- Sends a `User-Agent` header with every request for easy troubleshooting


## Requirements

- PHP 8.2 or higher
- Composer
- A UniFi OS Server or UniFi OS console with API key access to the Network Application
- Network access to your UniFi Controller, or remote access through the [Site Manager connector](#site-manager-connector-remote-access)


## API Version Compatibility

The official API documentation is published at [developer.ui.com](https://developer.ui.com/network/v10.6.106/gettingstarted).
The version in the documentation URL is the UniFi Network Application version the API belongs to; the REST paths
themselves stay under `/v1/...`. Newer Network Application versions add endpoints and fields over time, so some
features require a minimum version on your console:

| Feature | Minimum Network version |
|---------|-------------------------|
| All endpoints in this client except the ones listed below | 10.1.84 |
| Switching endpoints (`switching()`) | 10.2.x |
| WiFi `channel2gLockedTo6`, `dtimPeriod2gLockedTo3` (required), `dnsAssistanceConfiguration`, `handoffSuggestionsConfiguration`, Enhanced Open `encryption` | 10.6.106 |
| Firewall policy `reportFlowEnabled` (create, update and patch) | 11.0.89 |
| Device radio `txPowerMode`, `802.11ah` standard and `0.9` GHz frequency | 11.0.89 |

Use `$apiClient->applicationInfo()->get()` to check which version your console runs.


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

// Create a standard WPA2/WPA3 WiFi network
$apiClient->wifiBroadcasts()->create([
    'type' => 'STANDARD',                         // or 'IOT_OPTIMIZED'
    'name' => 'My WiFi',
    'enabled' => true,
    'network' => ['type' => 'NATIVE'],            // or ['type' => 'SPECIFIC', 'networkId' => 'network-uuid']
    'securityConfiguration' => [
        'type' => 'WPA2_WPA3_PERSONAL',
        'passphrase' => 'a-strong-passphrase',
        'pmfMode' => 'OPTIONAL',
        'wpa3FastRoamingEnabled' => false,
        'saeConfiguration' => ['anticloggingThresholdSeconds' => 5, 'syncTimeSeconds' => 5],
    ],
    'broadcastingFrequenciesGHz' => [2.4, 5],     // numbers, not strings
    'hideName' => false,
    'clientIsolationEnabled' => false,
    'multicastToUnicastConversionEnabled' => false,
    'uapsdEnabled' => false,
    'advertiseDeviceName' => false,
    'arpProxyEnabled' => false,
    'bssTransitionEnabled' => true,
    'channel2gLockedTo6' => false,                // required as of Network 10.6.106
    'dtimPeriod2gLockedTo3' => false,             // required as of Network 10.6.106
]);

// Delete a WiFi network
$apiClient->wifiBroadcasts()->delete('wifi-uuid-here');
```

Updating a WiFi broadcast uses `PUT`, so the body must contain all required fields (the same as for
`create()`), not only the fields you want to change.

Optional settings for `STANDARD` broadcasts added in Network 10.6:

```php
// DNS assistance: AUTO, or MANUAL with up to two failover DNS servers
'dnsAssistanceConfiguration' => ['mode' => 'MANUAL', 'servers' => ['1.1.1.1', '8.8.8.8']],

// Suggest low-signal clients to roam to a better AP (RSSI thresholds in dBm, null disables a band)
'handoffSuggestionsConfiguration' => ['band5GHzRssiThreshold' => -70, 'band6GHzRssiThreshold' => -80],

// Enhanced Open (OWE) for open networks
'securityConfiguration' => ['type' => 'OPEN', 'encryption' => 'ENHANCED_OPEN'], // or ENHANCED_OPEN_WITH_TRANSITION
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
    'action' => ['type' => 'BLOCK'],                    // ALLOW (requires 'allowReturnTraffic'), BLOCK or REJECT
    'source' => ['zoneId' => 'source-zone-uuid'],
    'destination' => ['zoneId' => 'destination-zone-uuid'],
    'ipProtocolScope' => ['ipVersion' => 'IPV4_AND_IPV6'], // IPV4, IPV6 or IPV4_AND_IPV6
    'loggingEnabled' => false,
]);

// Patch a firewall policy (PATCH only accepts loggingEnabled, and reportFlowEnabled on Network 11.0+)
$apiClient->firewall()->patchPolicy('policy-uuid', [
    'loggingEnabled' => true,
]);

// Full update (PUT) - send all required fields, not just the changed ones
$apiClient->firewall()->updatePolicy('policy-uuid', [
    'name' => 'Block IoT to LAN',
    'enabled' => false,
    'action' => ['type' => 'BLOCK'],
    'source' => ['zoneId' => 'source-zone-uuid'],
    'destination' => ['zoneId' => 'destination-zone-uuid'],
    'ipProtocolScope' => ['ipVersion' => 'IPV4_AND_IPV6'],
    'loggingEnabled' => false,
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

// Create an IPv4 ACL rule
$apiClient->aclRules()->create([
    'type' => 'IPV4',                       // or 'MAC' (which requires 'networkIdFilter')
    'name' => 'Block guest subnet to NAS',
    'enabled' => true,
    'action' => 'BLOCK',                    // or 'ALLOW'
    'sourceFilter' => [
        'type' => 'IP_ADDRESSES_OR_SUBNETS', // or NETWORKS / PORTS
        'ipAddressesOrSubnets' => ['192.168.50.0/24'],
    ],
    'destinationFilter' => [
        'type' => 'IP_ADDRESSES_OR_SUBNETS',
        'ipAddressesOrSubnets' => ['192.168.1.10'],
        'portFilter' => [445],
    ],
    'protocolFilter' => ['TCP'],            // omit to apply to all protocols
]);
// Note: the 'index' field is deprecated and has no effect - use updateOrdering() instead

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
// Types: A_RECORD, AAAA_RECORD, CNAME_RECORD, MX_RECORD, TXT_RECORD, SRV_RECORD, FORWARD_DOMAIN
$apiClient->dnsPolicies()->create([
    'type' => 'A_RECORD',
    'enabled' => true,
    'domain' => 'myapp.local',
    'ipv4Address' => '192.168.1.100',
    'ttlSeconds' => 3600,
]);

// Create a DNS CNAME record
$apiClient->dnsPolicies()->create([
    'type' => 'CNAME_RECORD',
    'enabled' => true,
    'domain' => 'alias.local',
    'targetDomain' => 'myapp.local',
    'ttlSeconds' => 3600,
]);

// Update a DNS policy (PUT) - send all required fields for the record type
$apiClient->dnsPolicies()->update('dns-policy-uuid', [
    'type' => 'A_RECORD',
    'enabled' => false,
    'domain' => 'myapp.local',
    'ipv4Address' => '192.168.1.200',
    'ttlSeconds' => 3600,
]);

// Delete a DNS policy
$apiClient->dnsPolicies()->delete('dns-policy-uuid');
```

### Switching (Switch Stacks, MC-LAG, LAGs)

All switching endpoints are read-only.

```php
<?php

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\LagFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\SwitchStackFilter;

// List switch stacks and get the details of one
$stacks = $apiClient->switching()->listSwitchStacks();
$stack = $apiClient->switching()->getSwitchStack('switch-stack-uuid');

// Find the stack that contains a specific switch
$stacks = $apiClient->switching()->listSwitchStacks(
    filter: SwitchStackFilter::unitsMacAddress()->contains('94:2a:6f:26:c6:ca')
);

// List MC-LAG domains and get the details of one
$domains = $apiClient->switching()->listMcLagDomains();
$domain = $apiClient->switching()->getMcLagDomain('mc-lag-domain-uuid');

// List LAGs (type is LOCAL, SWITCH_STACK or MULTI_CHASSIS)
$lags = $apiClient->switching()->listLags();
$localLags = $apiClient->switching()->listLags(filter: LagFilter::localLags());

// Find LAGs that include port 5 on any member
$lags = $apiClient->switching()->listLags(filter: LagFilter::membersPortIdxs()->contains(5));

// Get the details of a single LAG
$lag = $apiClient->switching()->getLag('lag-uuid');
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

// Set operations - devices with the access point feature
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::features()->contains('accessPoint')
);

// UUID values - passed as plain strings
$devices = $apiClient->devices()->listAdopted(
    filter: DeviceFilter::id()->in(['550e8400-e29b-41d4-a716-446655440000', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'])
);

// Timestamp values - pass a DateTimeInterface
$recentClients = $apiClient->clients()->list(
    filter: ClientFilter::connectedAt()->greaterThan(new DateTimeImmutable('-1 hour'))
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
- `AclRuleFilter` - For ACL rule filtering (incl. source/destination filter properties)
- `WifiBroadcastFilter` - For WiFi broadcast (SSID) filtering
- `VoucherFilter` - For hotspot voucher filtering (also usable for bulk voucher deletion)
- `FirewallZoneFilter` - For firewall zone filtering
- `TrafficMatchingListFilter` - For traffic matching list filtering
- `SwitchStackFilter`, `McLagDomainFilter`, `LagFilter` - For switching filtering

**Value Formatting:**

The filter builders format values the way the API expects them, so you can pass native PHP values:

| PHP value | Sent to the API as | Example |
|-----------|--------------------|---------|
| String | Single-quoted, `'` escaped as `''` | `name.eq('Erik''s Lab')` |
| UUID string | Unquoted | `id.eq(550e8400-e29b-41d4-a716-446655440000)` |
| `int` / `float` | Unquoted | `vlanId.eq(10)`, `broadcastingFrequenciesGHz.contains(2.4)` |
| `bool` | `true` / `false` | `enabled.eq(true)` |
| `DateTimeInterface` | Unquoted ISO 8601 in UTC | `connectedAt.gt(2026-01-29T12:39:11Z)` |
| Backed enum | Its value, formatted as above | `type.eq('WIRELESS')` |

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

- `type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `A_RECORD`, `AAAA_RECORD`, `CNAME_RECORD`, `MX_RECORD`, `TXT_RECORD`, `SRV_RECORD`, `FORWARD_DOMAIN`)
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `enabled` (BOOLEAN) - `eq`, `ne`
- `domain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `ipv4Address` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `ipv6Address` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `targetDomain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `mailServerDomain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `text` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `serverDomain` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `ipAddress` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `ttlSeconds` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`
- `priority` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`
- `service` (STRING) - `eq`, `ne`, `in`, `notIn`
- `protocol` (STRING) - `eq`, `ne`, `in`, `notIn`
- `port` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`
- `weight` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`

#### ACL Rule Filterable Properties

According to the official API specification, the following properties are filterable for ACL rules (`AclRuleFilter`):

- `type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `IPV4`, `MAC`)
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `enabled` (BOOLEAN) - `eq`, `ne`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `description` (STRING) - `isNull`, `isNotNull`, `eq`, `ne`, `in`, `notIn`, `like`
- `action` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `ALLOW`, `BLOCK`)
- `index` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`
- `protocolFilter` (SET(STRING)) - `isNull`, `isNotNull`, `contains`, `containsAny`, `containsAll`, `containsExactly`
- `networkId` (UUID) - `isNull`, `isNotNull`, `eq`, `ne`, `in`, `notIn`
- `enforcingDeviceFilter.deviceIds` (SET(UUID)) - `isNull`, `isNotNull`, `contains`, `containsAny`, `containsAll`, `containsExactly`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`
- `sourceFilter.type` / `destinationFilter.type` (STRING) - `isNull`, `isNotNull`, `eq`, `ne`, `in`, `notIn`
- `sourceFilter.ipAddressesOrSubnets` / `destinationFilter.ipAddressesOrSubnets` (SET(STRING)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `sourceFilter.portFilter` / `destinationFilter.portFilter` (SET(INTEGER)) - `isNull`, `isNotNull`, `contains`, `containsAny`, `containsAll`, `containsExactly`
- `sourceFilter.networkIds` / `destinationFilter.networkIds` (SET(UUID)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `sourceFilter.macAddresses` / `destinationFilter.macAddresses` (SET(STRING)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `sourceFilter.prefixLength` / `destinationFilter.prefixLength` (INTEGER) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`, `in`, `notIn`

#### WiFi Broadcast Filterable Properties

According to the official API specification, the following properties are filterable for WiFi broadcasts (`WifiBroadcastFilter`):

- `type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `STANDARD`, `IOT_OPTIMIZED`)
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `enabled` (BOOLEAN) - `eq`, `ne`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `broadcastingFrequenciesGHz` (SET(DECIMAL)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`
- `network.type` (STRING) - `eq`, `ne`, `in`, `notIn`, `isNull`, `isNotNull`
- `network.networkId` (UUID) - `eq`, `ne`, `in`, `notIn`
- `securityConfiguration.type` (STRING) - `eq`, `ne`, `in`, `notIn`
- `broadcastingDeviceFilter.type` (STRING) - `eq`, `ne`, `in`, `notIn`, `isNull`, `isNotNull`
- `broadcastingDeviceFilter.deviceIds` (SET(UUID)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `broadcastingDeviceFilter.deviceTagIds` (SET(UUID)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `hotspotConfiguration.type` (STRING) - `eq`, `ne`, `in`, `notIn`

#### Hotspot Voucher Filterable Properties

According to the official API specification, the following properties are filterable for vouchers (`VoucherFilter`), both when listing and when deleting vouchers:

- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `createdAt` (TIMESTAMP) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `code` (STRING) - `eq`, `ne`, `in`, `notIn`
- `authorizedGuestLimit` (INTEGER) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `authorizedGuestCount` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `activatedAt` (TIMESTAMP) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `expiresAt` (TIMESTAMP) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `expired` (BOOLEAN) - `eq`, `ne`
- `timeLimitMinutes` (INTEGER) - `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `dataUsageLimitMBytes` (INTEGER) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `rxRateLimitKbps` (INTEGER) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- `txRateLimitKbps` (INTEGER) - `isNull`, `isNotNull`, `eq`, `ne`, `gt`, `ge`, `lt`, `le`

#### Firewall Zone Filterable Properties

According to the official API specification, the following properties are filterable for firewall zones (`FirewallZoneFilter`):

- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`
- `metadata.configurable` (BOOLEAN) - `eq`, `ne`, `isNull`, `isNotNull`

> **Deprecated:** `FirewallZoneFilter::networkIds()`, `FirewallZoneFilter::emptyZones()` and
> `TrafficMatchingListFilter::type()` target properties the API does not accept as filters (it responds with
> `400 api.request.invalid-filter`). They are kept for backward compatibility and will be removed in 2.0.

#### Switching Filterable Properties

According to the official API specification, the following properties are filterable:

**Switch stacks** (`SwitchStackFilter`):
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `units.id` (SET(INTEGER)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `units.macAddress` (SET(STRING)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`

**MC-LAG domains** (`McLagDomainFilter`):
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `name` (STRING) - `eq`, `ne`, `in`, `notIn`, `like`
- `peers.deviceId` (SET(UUID)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`

**LAGs** (`LagFilter`):
- `id` (UUID) - `eq`, `ne`, `in`, `notIn`
- `type` (STRING) - `eq`, `ne`, `in`, `notIn` (Valid values: `LOCAL`, `SWITCH_STACK`, `MULTI_CHASSIS`)
- `switchStackId` (UUID) - `eq`, `ne`, `in`, `notIn`, `isNull`, `isNotNull`
- `mcLagDomainId` (UUID) - `eq`, `ne`, `in`, `notIn`, `isNull`, `isNotNull`
- `members.deviceId` (SET(UUID)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `members.portIdxs` (SET(INTEGER)) - `contains`, `containsAny`, `containsAll`, `containsExactly`
- `metadata.origin` (STRING) - `eq`, `ne`, `in`, `notIn`

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

By default, a failed request (HTTP 4xx/5xx) is **returned** like any other response, not thrown. Check the
response, or call `throw()` to turn it into an exception:

```php
<?php

$response = $apiClient->devices()->get('550e8400-e29b-41d4-a716-446655440000');

if ($response->failed()) {
    // The body follows the API's error format
    echo $response->status();           // 404
    echo $response->json('code');       // api.resource-not-found
    echo $response->json('message');    // Requested resource does not exist
    echo $response->json('requestId');  // useful when looking up 500 errors in the controller logs
}

// Or throw a ClientException (4xx) / ServerException (5xx) for a failed response
$device = $response->throw()->json();
```

To have **every** failed response throw, enable `throwOnErrors` (opt-in, available since 1.2.0):

```php
<?php

use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;

$apiClient = new UnifiClient('https://192.168.1.1', 'your-api-key', throwOnErrors: true);
// or on an existing client: $apiClient->throwOnErrors();

try {
    $device = $apiClient->devices()->get('550e8400-e29b-41d4-a716-446655440000')->json();
} catch (NotFoundException $e) {
    // Specific 4xx subclasses exist, e.g. UnauthorizedException, ForbiddenException, TooManyRequestsException
    echo $e->getResponse()->json('message');
} catch (ClientException $e) {
    // Any other 4xx error
    echo 'Client error: ' . $e->getResponse()->status();
} catch (ServerException $e) {
    // 5xx errors
    echo 'Server error: ' . $e->getResponse()->status();
} catch (FatalRequestException $e) {
    // Network issues, timeouts, SSL errors
    echo 'Connection failed: ' . $e->getMessage();
}
```

Regardless of this setting:
- **Network problems** (connection refused, DNS failures, timeouts, SSL errors) always throw a `FatalRequestException`.
- A missing site ID throws a `RuntimeException` **before** any request is sent.
- An invalid API key is rejected by UniFi OS itself with `401` and a different body (`{"error": {"code": 401, ...}}`)
  than the API's own error format.

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

### Site Manager Connector (Remote Access)

If there is no direct network path to your console, you can reach its Network API through the
[Site Manager API](https://developer.ui.com/site-manager/v1.0.0/gettingstarted) connector at `api.ui.com`. Requests
are proxied by Ubiquiti's cloud to the console, without a VPN or port forwarding:

```php
<?php

$apiClient = UnifiClient::connectViaSiteManager(
    consoleId: 'your-console-id',
    apiKey: 'your-site-manager-api-key'
);

// Use the client exactly like a direct connection
$sites = $apiClient->sites()->list();
$apiClient->setSiteId('your-site-uuid');
$devices = $apiClient->devices()->listAdopted();
```

Requests are sent to `https://api.ui.com/v1/connector/consoles/{consoleId}/network/integration/...`, which the
connector forwards to `/proxy/network/integration/...` on the console.

- **API key:** use a Site Manager API key generated at [unifi.ui.com](https://unifi.ui.com), not a key from the
  Network Application's Integrations page. Non-organization keys can only reach consoles owned by the key's owner;
  organization keys can reach any console in the organization.
- **Console ID:** the host ID shown in the console URL on unifi.ui.com (`https://unifi.ui.com/consoles/{consoleId}/...`).
- **Console firmware:** UniFi OS 5.0.3 or later, and the console must be online and connected to UI.com.
- **SSL:** always verified, since `api.ui.com` uses a publicly trusted certificate.
- **Limits:** 100 requests per minute per console, a 25 second timeout per request and a 10 MB response size limit,
  so use pagination with a sensible `limit` for large lists. Expect extra latency compared to direct access.

### Version Information

You can retrieve the client library version at runtime, which is useful for troubleshooting and logging:

```php
<?php

echo $apiClient->getVersion(); // e.g., "1.2.0"
```

The version is also sent with every API request as a `User-Agent` header (`unifi-api-client-php/1.2.0`), which can help
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
| `switching()` | Read-only switching topology: switch stacks, MC-LAG domains and LAGs |
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

## Upgrading to 1.2.0

1.2.0 is backward compatible for normal use: no public methods were removed or renamed, and the default
error handling is unchanged. A few behaviours changed because they never worked against the API:

| Change | Before | Now |
|--------|--------|-----|
| UUID values in filters | quoted: `id.eq('550e8400-…')`, rejected by the API | unquoted: `id.eq(550e8400-…)` |
| Single quotes in filter values | escaped as `\'`, rejected by the API | escaped as `''` (`'Erik''s Lab'`) |
| `DnsPolicyFilter` presets (`aRecords()`, …) | filtered on `A`, `CNAME`, …, never matched | filter on `A_RECORD`, `CNAME_RECORD`, … |
| Timestamp filters | only possible as raw strings | pass a `DateTimeInterface` to the builders |

If your code compared filter strings produced by the builders (e.g. in your own tests), update the expected
strings for UUID values and single quotes.

**Deprecated** (still available, removed in 2.0): `FirewallZoneFilter::networkIds()`,
`FirewallZoneFilter::emptyZones()` and `TrafficMatchingListFilter::type()`. The API rejects these properties as
filters (`400 api.request.invalid-filter`), so any code using them did not work before either.

**Wider parameter types:** the `Filter` comparison methods (`equals()`, `greaterThan()`, `contains()`, …) accept
`float` and `DateTimeInterface`, and `sites()->list()`, `devices()->listPending()` and `hotspot()->deleteVouchers()`
accept Filter objects. This only affects you if you **extend** these classes and override those methods: widen
the parameter types in your overrides accordingly.

**Error handling:** failed responses (4xx/5xx) were always returned rather than thrown, even though earlier
documentation said otherwise. That behaviour is unchanged; to get exceptions, enable the new `throwOnErrors`
option (see [Error Handling](#error-handling)).

**Network Application 10.6.106+:** the API specification marks `channel2gLockedTo6` and `dtimPeriod2gLockedTo3`
as required for WiFi broadcast create/update requests on these versions. Add both fields (e.g. `false`) to existing
create/update payloads.


## Migrating from the Legacy Client

If you're migrating from the [legacy UniFi API client](https://github.com/Art-of-WiFi/UniFi-API-client), this new Saloon-based client offers:

- Modern PHP 8.2+ syntax with typed properties
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
