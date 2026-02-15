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
    $listResponse = $this->client->networks()->list(limit: 1);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No networks available for testing.');
    }

    $networkId = $list['data'][0]['id'];
    $response = $this->client->networks()->getReferences($networkId);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toBeArray();
})->group('integration');
