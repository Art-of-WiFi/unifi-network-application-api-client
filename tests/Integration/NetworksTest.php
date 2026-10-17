<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list networks', function () {
    $response = $this->client->networks()->list();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data')
        ->toHaveKey('offset')
        ->toHaveKey('limit')
        ->toHaveKey('totalCount');
    expect($data['data'])->toBeArray();
})->group('integration');

test('can list networks with pagination', function () {
    $response = $this->client->networks()->list(offset: 0, limit: 2);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data['limit'])->toBe(2);
})->group('integration');

test('can get network details', function () {
    $listResponse = $this->client->networks()->list(limit: 1);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No networks available for testing.');
    }

    $networkId = $list['data'][0]['id'];
    $response = $this->client->networks()->get($networkId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $networkId);
    // Single-item fetch should NOT have a 'data' wrapper
    expect($data)->not->toHaveKey('offset');
    expect($data)->not->toHaveKey('limit');
})->group('integration');

test('can get network references', function () {
    $listResponse = $this->client->networks()->list(limit: 200);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No networks available for testing.');
    }

    // Prefer a non-default network: some controller versions (e.g. 11.0.89) return a 500
    // api.unexpected-error when fetching references for the default network
    $nonDefaultNetworks = array_values(array_filter($list['data'], fn (array $network) => ! ($network['default'] ?? false)));
    $network = $nonDefaultNetworks[0] ?? $list['data'][0];

    $response = $this->client->networks()->getReferences($network['id']);

    if ($response->status() === 500 && ($network['default'] ?? false)) {
        $this->markTestSkipped('Controller returns a server error for references of the default network.');
    }

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('referenceResources');
    expect($data['referenceResources'])->toBeArray();
})->group('integration');
