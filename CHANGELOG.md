# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2026-10-17

Supports UniFi Network Application API version 10.6.106 (the latest public specification on developer.ui.com),
verified against Network Application 11.0.89. See "Upgrading to 1.2.0" in the README for behaviour changes.

### Added
- Switching resource (`$client->switching()`) with read-only access to switch stacks, MC-LAG domains and LAGs
  (`listSwitchStacks()`, `getSwitchStack()`, `listMcLagDomains()`, `getMcLagDomain()`, `listLags()`, `getLag()`)
- `SwitchStackFilter`, `McLagDomainFilter` and `LagFilter` filter builders, including `LagFilter::localLags()`,
  `switchStackLags()` and `mcLags()` presets
- All filterable properties from the API specification are now covered by the filter builders:
  - `AclRuleFilter`: `description()`, `index()`, `protocolFilter()`, `networkId()`, `enforcingDeviceIds()`,
    `metadataOrigin()` and the source/destination filter properties (`sourceFilterType()`,
    `sourceIpAddressesOrSubnets()`, `sourcePortFilter()`, `sourceNetworkIds()`, `sourceMacAddresses()`,
    `sourcePrefixLength()` and their `destination*` counterparts)
  - `WifiBroadcastFilter`: `broadcastingFrequenciesGHz()`, `metadataOrigin()`, `networkType()`, `networkId()`,
    `securityType()`, `broadcastingDeviceFilterType()`, `broadcastingDeviceIds()`, `broadcastingDeviceTagIds()`,
    `hotspotType()`
  - `VoucherFilter`: `createdAt()`, `authorizedGuestLimit()`, `authorizedGuestCount()`, `activatedAt()`,
    `expiresAt()`, `timeLimitMinutes()`, `dataUsageLimitMBytes()`, `rxRateLimitKbps()`, `txRateLimitKbps()` and
    an `unused()` preset
  - `FirewallZoneFilter`: `metadataOrigin()`, `metadataConfigurable()` and a `customZones()` preset
- Comparison operators accept `DateTimeInterface` values for TIMESTAMP properties, formatted as unquoted
  ISO 8601 UTC timestamps (e.g. `connectedAt.gt(2026-01-29T12:39:11Z)`)
- `sites()->list()` and `devices()->listPending()` now accept Filter objects, like all other list methods
- Integration tests that send filter builder output to a live controller to verify the API accepts it
- Site Manager connector support: `UnifiClient::connectViaSiteManager($consoleId, $apiKey)` reaches a console's
  Network API remotely through `api.ui.com` (`UnifiConnector::viaSiteManager()`, `isSiteManagerProxy()` and
  `getSiteManagerConsoleId()` on the connector). SSL verification is always enabled in this mode
- Opt-in `throwOnErrors` to throw a `ClientException` (4xx) or `ServerException` (5xx) subclass for every failed
  response: a new constructor argument (`new UnifiClient($url, $key, throwOnErrors: true)`), `$client->throwOnErrors()`
  and a `throwOnErrors` argument on `connectViaSiteManager()`. The default behaviour is unchanged
- `hotspot()->deleteVouchers()` accepts a `VoucherFilter` object, e.g. `deleteVouchers(VoucherFilter::expiredOnly())`
- Mocked request tests for every resource method (HTTP method, path, query and body per the API specification) and
  error handling tests for both the default and the `throwOnErrors` behaviour

### Changed
- `Filter::contains()` now accepts `string|int|float|DateTimeInterface`, and `equals()`, `notEquals()`,
  `greaterThan()`, `greaterThanOrEqual()`, `lessThan()`, `lessThanOrEqual()` (and their shorthands) also accept
  `float` and `DateTimeInterface`. Note: subclasses of `Filter` that override these methods must widen their
  parameter types accordingly

### Deprecated
- `FirewallZoneFilter::networkIds()`, `FirewallZoneFilter::emptyZones()` and `TrafficMatchingListFilter::type()`:
  these target properties the API does not accept as filters (`400 api.request.invalid-filter`). They will be
  removed in 2.0

### Fixed
- UUID values in filters are no longer quoted. The API rejected quoted UUIDs (`expected UUID, actual STRING`),
  so filters on `id`, zone IDs, network IDs, device IDs and similar properties never worked with the builders
- Single quotes in filter values are now escaped by doubling them (`'Erik''s Lab'`), as required by the API
  filtering syntax. Previously a backslash was used, which made the API reject any filter value containing
  an apostrophe with `400 api.request.invalid-filter`
- `SET(INTEGER)` and `SET(DECIMAL)` properties (e.g. `members.portIdxs`, `units.id`,
  `broadcastingFrequenciesGHz`) could not be filtered with `contains()`, which only accepted strings
- Operator documentation for `DnsPolicyFilter` now matches the API specification (`like` on address
  properties, `in`/`notIn` on integer properties)
- `DnsPolicyFilter` record type presets (`aRecords()`, `aaaaRecords()`, `cnameRecords()`, `mxRecords()`,
  `txtRecords()`, `srvRecords()`) now use the type values defined by the API (`A_RECORD`, `AAAA_RECORD`, …);
  they previously filtered on `A`, `AAAA`, etc. and could never match a policy
- README and example payloads corrected to match the API specification: firewall policy `action` is an object
  and `ipProtocolScope`/`loggingEnabled` are required, firewall policy PATCH only accepts `loggingEnabled`
  (and `reportFlowEnabled` on Network 11.0+), DNS policy types use the `*_RECORD` values, WiFi broadcast
  frequencies are numbers, and PUT updates must send all required fields
- WiFi example read legacy field names (`security`, `wpa_mode`, `is_guest`) that do not exist in the official API
- Error handling documentation (README, example 06, docblocks) claimed 4xx/5xx responses throw a `ClientException`/
  `ServerException`. They are returned as responses unless `throwOnErrors` is enabled; the try/catch examples never
  caught anything. The docs now show `$response->failed()` / `$response->throw()` and the new opt-in
- The `force` parameter of `aclRules()->delete()`, `firewall()->deleteZone()` and `trafficMatchingLists()->delete()`
  is now documented as not being defined for these endpoints in the API specification

### Documentation
- API version compatibility table listing the minimum Network Application version per feature
- WiFi broadcast fields added in Network 10.6.106 (`channel2gLockedTo6`, `dtimPeriod2gLockedTo3`,
  `dnsAssistanceConfiguration`, `handoffSuggestionsConfiguration`, Enhanced Open `encryption`) and
  Network 11.0.89 additions (firewall policy `reportFlowEnabled`, device radio `txPowerMode`)
- Requirements updated to PHP 8.2 and Saloon v4

## [1.1.0] - 2026-03-26

### Changed
- Upgraded Saloon from v3 to v4 (security release addressing three CVEs)
- Minimum PHP version raised from 8.1 to 8.2 (required by Saloon v4)

## [1.0.0] - 2026-02-17

Initial public release. Supports UniFi Network Application API version 10.1.84.

### Fixed
- Fixed API authentication issue where X-API-KEY header was not being sent correctly, causing 401 Unauthorized errors
- Moved API key from TokenAuthenticator to default headers for proper authentication
- Fixed example code to use defensive array access patterns with null coalescing operators to handle missing keys across different controller API versions
- Fixed incorrect endpoint paths throughout the codebase to match OpenAPI specification:
  - **Devices**: Removed incorrect `/adopted` segment from all device endpoints
    - List adopted devices: `/v1/sites/{siteId}/devices` (was `/v1/sites/{siteId}/devices/adopted`)
    - Get device details: `/v1/sites/{siteId}/devices/{deviceId}` (was `/v1/sites/{siteId}/devices/adopted/{deviceId}`)
    - Get device statistics: `/v1/sites/{siteId}/devices/{deviceId}/statistics/latest` (was `/v1/sites/{siteId}/devices/adopted/{deviceId}/statistics/latest`)
    - Execute device action: `/v1/sites/{siteId}/devices/{deviceId}/actions` (was `/v1/sites/{siteId}/devices/adopted/{deviceId}/action`)
    - Execute port action: `/v1/sites/{siteId}/devices/{deviceId}/interfaces/ports/{portIdx}/actions` (was `/v1/sites/{siteId}/devices/adopted/{deviceId}/ports/{portId}/action`)
  - **Clients**: Removed incorrect `/connected` segment from all client endpoints
    - List clients: `/v1/sites/{siteId}/clients` (was `/v1/sites/{siteId}/clients/connected`)
    - Get client details: `/v1/sites/{siteId}/clients/{clientId}` (was `/v1/sites/{siteId}/clients/connected/{clientId}`)
    - Execute client action: `/v1/sites/{siteId}/clients/{clientId}/actions` (was `/v1/sites/{siteId}/clients/connected/{clientId}/action`)
  - **Pending devices**: Now `/v1/pending-devices` (global endpoint, no siteId required) instead of `/v1/sites/{siteId}/devices/pending`
  - **Action endpoints**: Changed from singular `/action` to plural `/actions` to match API spec
  - **Port parameters**: Changed `portId` (string) to `portIdx` (int) to match API spec requirement for port index

### Added
- Library version constant (`UnifiConnector::VERSION`) and `UnifiClient::getVersion()` accessor
- `User-Agent` header (`unifi-api-client-php/{version}`) sent with every API request for troubleshooting
- Supporting Resources endpoints for reference data:
  - WAN interfaces listing
  - Site-to-site VPN tunnels listing
  - VPN servers listing
  - RADIUS profiles listing
  - Device tags listing
  - DPI (Deep Packet Inspection) categories listing
  - DPI applications listing
  - Countries listing (ISO codes for regulatory compliance)
- DNS Policies endpoints (CRUD)
- Filter builder classes for type-safe API filtering
- Saloon v3-based HTTP client implementation
- Full support for UniFi Network Application API version 10.1.84
- Fluent interface with method chaining
- Resources for all major API categories:
  - Application Info
  - Sites
  - Devices (adopted and pending)
  - Clients (connected clients)
  - Networks (VLAN, DHCP, routing)
  - WiFi Broadcasts (SSID management)
  - Hotspot (voucher management)
  - Firewall (zones and policies)
  - ACL Rules
  - DNS Policies
  - Traffic Matching Lists
- Comprehensive documentation with examples
- Error handling with typed exceptions
- Support for pagination and filtering
- SSL certificate verification toggle
- PHP 8.1+ support with typed properties

### Documentation
- Comprehensive README with quick start guide
- 10 detailed example files covering common use cases
- Inline PHPDoc comments for IDE auto-completion
