# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
- Initial release of the UniFi Network Application API Client
- Saloon v3-based HTTP client implementation
- Full support for UniFi Network API v10.1.39
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
  - Traffic Matching Lists
- Comprehensive documentation with examples
- Error handling with typed exceptions
- Support for pagination and filtering
- SSL certificate verification toggle
- PHP 8.1+ support with typed properties

### Documentation
- Comprehensive README with quick start guide
- 6 detailed example files covering common use cases
- Inline PHPDoc comments for IDE auto-completion
- Migration guide from legacy API client

## [1.0.0] - 2026-02-17

Initial public release.
