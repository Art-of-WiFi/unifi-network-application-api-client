<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list WAN interfaces', function () {
    $response = $this->client->supportingResources()->listWanInterfaces();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list site-to-site VPN tunnels', function () {
    $response = $this->client->supportingResources()->listSiteToSiteVpnTunnels();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list VPN servers', function () {
    $response = $this->client->supportingResources()->listVpnServers();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list RADIUS profiles', function () {
    $response = $this->client->supportingResources()->listRadiusProfiles();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list device tags', function () {
    $response = $this->client->supportingResources()->listDeviceTags();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list DPI categories', function () {
    $response = $this->client->supportingResources()->listDpiCategories();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list DPI applications', function () {
    $response = $this->client->supportingResources()->listDpiApplications();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can list countries', function () {
    $response = $this->client->supportingResources()->listCountries();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
    expect($data['data'])->not->toBeEmpty();
})->group('integration');
