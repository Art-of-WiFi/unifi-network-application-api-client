# UniFi API Client Examples

This directory contains working examples demonstrating various features and use cases of the UniFi Network Application API Client.

## Getting Started

Before running these examples, make sure you have:

1. Installed the package via Composer: `composer install`
2. Generated an API key from your UniFi Network Application (Settings → Integrations)
3. Created a copy of the `config-template.php` file as `config.php` and updated it with your controller details.

## Examples Overview

### 01. Basic Usage
**File:** `01-basic-usage.php`

Learn the fundamentals of using the client:
- Initializing the UniFi client
- Getting application information
- Listing sites
- Basic device and client queries
- Understanding the response structure

**Start here if you're new to this library!**

### 02. Device Management
**File:** `02-device-management.php`

Comprehensive device operations:
- Listing adopted devices
- Getting device details and statistics
- Listing pending (unadopted) devices
- Executing device actions (reboot, locate, upgrade)
- Working with device ports

### 03. Client Operations
**File:** `03-client-operations.php`

Managing connected clients:
- Listing all connected clients
- Filtering clients by type (wired/wireless/guest)
- Getting detailed client information
- Authorizing and unauthorizing guest clients
- Blocking and unblocking clients

### 04. Network Configuration
**File:** `04-network-configuration.php`

Network management operations:
- Listing networks
- Creating new networks with VLAN configuration
- Updating network settings
- Deleting networks
- Configuring DHCP and DNS

### 05. WiFi Management
**File:** `05-wifi-management.php`

WiFi broadcast (SSID) operations:
- Listing WiFi networks
- Creating new SSIDs
- Updating WiFi settings (password, security, etc.)
- Deleting WiFi networks
- Configuring guest networks and portals

### 06. Error Handling
**File:** `06-error-handling.php`

Proper error handling techniques:
- Catching different exception types
- Handling HTTP errors (4xx, 5xx)
- Dealing with invalid parameters
- Authentication errors
- Graceful degradation and fallback strategies

### 07. Supporting Resources
**File:** `07-supporting-resources.php`

Working with reference data endpoints:
- Listing DPI categories and applications
- Retrieving country codes
- Accessing WAN interface definitions
- Managing RADIUS profiles
- Listing device tags
- Viewing VPN configurations

## Running the Examples

Each example is a standalone PHP script that can be run from the command line:

```bash
php examples/01-basic-usage.php
```

## Important Notes

### Safety First

Many examples include **commented-out** sections for operations that make changes to your UniFi configuration (creating, updating, or deleting resources).

These are commented out for your safety. **Read and understand the code before uncommenting and running it.**

### Configuration

Update the configuration variables in your own copy of the config-template.php file:

```php
return [
    'base_url'   => 'https://mycontroller.com:443', // Your UniFi Controller URL
    'api_key'    => 'your-api-key-here', // Your API key from the Integrations page
    'site_id'    => 'your-site-id-here', // The UUID of the site to use in specific examples (from example 01)
    'verify_ssl' => false // Set to true if you have a valid SSL certificate installed on the controller
];
```

### SSL Verification

The examples use the `verify_ssl` value from your config.php. For local development with self-signed certificates we recommend setting that value to `false`. For production environments with valid SSL certificates, set this to `true`:


## Need Help?

- Review the main [README.md](../README.md) for detailed documentation
- Check the [official UniFi API documentation](https://developer.ui.com)
- Open an issue on GitHub if you encounter problems

## Contributing Examples

Have a useful example to share? We welcome contributions! Please see [CONTRIBUTING.md](../CONTRIBUTING.md) for guidelines.
