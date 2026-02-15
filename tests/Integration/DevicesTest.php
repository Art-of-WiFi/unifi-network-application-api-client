<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list adopted devices', function () {
    $response = $this->client->devices()->listAdopted();

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

test('can list adopted devices with pagination', function () {
    $response = $this->client->devices()->listAdopted(offset: 0, limit: 1);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data['limit'])->toBe(1);
})->group('integration');

test('can list pending devices', function () {
    $response = $this->client->devices()->listPending();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data');
})->group('integration');

test('can get device details', function () {
    $listResponse = $this->client->devices()->listAdopted(limit: 1);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No adopted devices available for testing.');
    }

    $deviceId = $list['data'][0]['id'];
    $response = $this->client->devices()->get($deviceId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $deviceId);
    // Single-item fetch should NOT have a 'data' wrapper
    expect($data)->not->toHaveKey('offset');
    expect($data)->not->toHaveKey('limit');
})->group('integration');

test('can get device statistics', function () {
    $listResponse = $this->client->devices()->listAdopted(limit: 1);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No adopted devices available for testing.');
    }

    $deviceId = $list['data'][0]['id'];
    $response = $this->client->devices()->getStatistics($deviceId);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toBeArray();
})->group('integration');
