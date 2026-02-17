<?php

/**
 * Configuration Template
 *
 * Copy this file to config.php and fill in your UniFi Controller URL, API key, and SSL certificate settings.
 */

return [
    'base_url' => 'https://mycontroller.com:443', // Your UniFi Controller URL
    'api_key' => '', // Your API key from the Integrations page
    'site_id' => '', // The UUID of the site to use in specific examples
    'verify_ssl' => false, // Set to true if you have a valid SSL certificate installed on the controller
];
