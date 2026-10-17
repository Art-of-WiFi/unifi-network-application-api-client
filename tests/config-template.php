<?php

/**
 * Test Configuration Template
 *
 * Copy this file to config.php and fill in your UniFi Controller URL, API key, and SSL certificate settings.
 * The config.php file is gitignored so your credentials stay safe.
 */

return [
    'base_url' => 'https://mycontroller.com:443', // Your UniFi Controller URL
    'api_key' => '', // Your API key from the Integrations page
    'site_id' => '', // The UUID of the site to use in integration tests
    'verify_ssl' => false, // Set to true if you have a valid SSL certificate installed on the controller

    // Optional: Site Manager connector tests (tests/Integration/SiteManagerProxyTest.php are skipped when empty)
    'site_manager_console_id' => '', // The console (host) ID from the unifi.ui.com URL: https://unifi.ui.com/consoles/{id}/...
    'site_manager_api_key' => '', // A Site Manager API key generated at unifi.ui.com (not the local Network API key)
];
