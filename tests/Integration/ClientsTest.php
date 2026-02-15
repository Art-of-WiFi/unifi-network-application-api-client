<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list connected clients', function () {
    $response = $this->client->clients()->list();

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

test('can list connected clients with pagination', function () {
    $response = $this->client->clients()->list(offset: 0, limit: 5);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data['limit'])->toBe(5);
})->group('integration');

test('can get client details', function () {
    $listResponse = $this->client->clients()->list(limit: 1);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No connected clients available for testing.');
    }

    $clientId = $list['data'][0]['id'];
    $response = $this->client->clients()->get($clientId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $clientId);
    // Single-item fetch should NOT have a 'data' wrapper
    expect($data)->not->toHaveKey('offset');
    expect($data)->not->toHaveKey('limit');
})->group('integration');
