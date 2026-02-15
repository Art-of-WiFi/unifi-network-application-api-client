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

test('timeout is set to 30 seconds', function () {
    $connector = new UnifiConnector('https://192.168.1.1', 'test-key');

    $config = $connector->config()->all();
    expect($config)->toHaveKey('timeout', 30);
});
