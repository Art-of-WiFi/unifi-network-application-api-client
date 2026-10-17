<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiConnector;

test('resolveBaseUrl() appends integration path', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key');
    expect($connector->resolveBaseUrl())
        ->toBe('https://192.168.1.1/proxy/network/integration');
});

test('resolveBaseUrl() trims trailing slash', function () {
    $connector = new UnifiConnector('https://192.168.1.1/', 'test-key');
    expect($connector->resolveBaseUrl())
        ->toBe('https://192.168.1.1/proxy/network/integration');
});

test('resolveBaseUrl() works with port', function () {
    $connector = new UnifiConnector('https://192.168.1.1:8443', 'test-key');
    expect($connector->resolveBaseUrl())
        ->toBe('https://192.168.1.1:8443/proxy/network/integration');
});

test('default headers include API key', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'my-api-key-123');

    $headers = $connector->headers()->all();
    expect($headers)->toHaveKey('X-API-KEY', 'my-api-key-123');
});

test('default headers include Accept and Content-Type', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key');

    $headers = $connector->headers()->all();
    expect($headers)
        ->toHaveKey('Accept', 'application/json')
        ->toHaveKey('Content-Type', 'application/json');
});

test('SSL verification defaults to true', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key');

    $config = $connector->config()->all();
    expect($config)->toHaveKey('verify', true);
});

test('SSL verification can be disabled', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key', false);

    $config = $connector->config()->all();
    expect($config)->toHaveKey('verify', false);
});

test('direct connections are not Site Manager proxied', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key');

    expect($connector->isSiteManagerProxy())->toBeFalse();
    expect($connector->getSiteManagerConsoleId())->toBeNull();
});

test('viaSiteManager() routes requests through the Site Manager connector', function () {
    $connector = UnifiConnector::viaSiteManager('245A4CA2341500000000:48913759', 'site-manager-key');

    expect($connector->resolveBaseUrl())
        ->toBe('https://api.ui.com/v1/connector/consoles/245A4CA2341500000000:48913759/network/integration');
    expect($connector->isSiteManagerProxy())->toBeTrue();
    expect($connector->getSiteManagerConsoleId())->toBe('245A4CA2341500000000:48913759');
});

test('viaSiteManager() sends the Site Manager key in the X-API-KEY header', function () {
    $connector = UnifiConnector::viaSiteManager('console-id', 'site-manager-key');

    expect($connector->headers()->all())->toHaveKey('X-API-KEY', 'site-manager-key');
});

test('viaSiteManager() always verifies SSL', function () {
    $connector = UnifiConnector::viaSiteManager('console-id', 'site-manager-key');

    expect($connector->config()->all())->toHaveKey('verify', true);
});

test('viaSiteManager() trims the console ID and API key', function () {
    $connector = UnifiConnector::viaSiteManager('  console-id  ', '  site-manager-key  ');

    expect($connector->getSiteManagerConsoleId())->toBe('console-id');
    expect($connector->headers()->all())->toHaveKey('X-API-KEY', 'site-manager-key');
});

test('viaSiteManager() rejects an empty console ID', function () {
    UnifiConnector::viaSiteManager('  ', 'site-manager-key');
})->throws(InvalidArgumentException::class, 'Console ID cannot be empty');

test('viaSiteManager() rejects an empty API key', function () {
    UnifiConnector::viaSiteManager('console-id', '');
})->throws(InvalidArgumentException::class, 'Site Manager API key cannot be empty');

test('timeout is set to 30 seconds', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key');

    $config = $connector->config()->all();
    expect($config)->toHaveKey('timeout', 30);
});
