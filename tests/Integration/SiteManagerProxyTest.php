<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\getTestConfig;

/*
 * These tests reach the console through the Site Manager connector at api.ui.com.
 * They require 'site_manager_console_id' and 'site_manager_api_key' in tests/config.php
 * and are skipped otherwise. All calls are read-only.
 */

beforeEach(function () {
    $config = getTestConfig();

    if (empty($config['site_manager_console_id']) || empty($config['site_manager_api_key'])) {
        $this->markTestSkipped('No Site Manager configuration found. Add site_manager_console_id and site_manager_api_key to tests/config.php.');
    }

    $this->client = UnifiClient::connectViaSiteManager($config['site_manager_console_id'], $config['site_manager_api_key']);
    $this->siteId = $config['site_id'] ?? null;
});

test('can get application info through the Site Manager connector', function () {
    $response = $this->client->applicationInfo()->get();

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('applicationVersion');
})->group('integration');

test('can list sites through the Site Manager connector', function () {
    $response = $this->client->sites()->list();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data')
        ->toHaveKey('totalCount');
})->group('integration');

test('can list adopted devices through the Site Manager connector', function () {
    $siteId = $this->siteId ?: $this->client->sites()->list(limit: 1)->json('data.0.id');

    if (empty($siteId)) {
        $this->markTestSkipped('No site available for testing.');
    }

    $response = $this->client->setSiteId($siteId)->devices()->listAdopted(limit: 5);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('data');
})->group('integration');

test('a single-item fetch through the Site Manager connector has no data wrapper', function () {
    $siteId = $this->siteId ?: $this->client->sites()->list(limit: 1)->json('data.0.id');
    $networkId = $this->client->setSiteId($siteId)->networks()->list(limit: 1)->json('data.0.id');

    if (empty($networkId)) {
        $this->markTestSkipped('No networks available for testing.');
    }

    $data = $this->client->networks()->get($networkId)->json();

    expect($data)
        ->toHaveKey('id', $networkId)
        ->not->toHaveKey('data');
})->group('integration');
