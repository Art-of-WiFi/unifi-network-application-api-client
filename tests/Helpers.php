<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Tests;

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

/**
 * Load test configuration from tests/config.php
 *
 * @return array{base_url: string, api_key: string, site_id: string, verify_ssl: bool}|null
 */
function getTestConfig(): ?array
{
    $configPath = __DIR__.'/config.php';

    if (! file_exists($configPath)) {
        return null;
    }

    return require $configPath;
}

/**
 * Create a UnifiClient instance from the test configuration
 */
function createTestClient(): ?UnifiClient
{
    $config = getTestConfig();

    if ($config === null) {
        return null;
    }

    $client = new UnifiClient(
        $config['base_url'],
        $config['api_key'],
        $config['verify_ssl'] ?? false,
    );

    if (! empty($config['site_id'])) {
        $client->setSiteId($config['site_id']);
    }

    return $client;
}

/**
 * Skip the current test if no controller configuration is available
 */
function skipIfNoController(): void
{
    if (getTestConfig() === null) {
        test()->markTestSkipped(
            'No controller configuration found. Copy tests/config-template.php to tests/config.php and fill in your credentials.'
        );
    }
}
