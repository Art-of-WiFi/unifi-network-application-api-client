<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list sites', function () {
    $response = $this->client->sites()->list();

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

test('can list sites with pagination', function () {
    $response = $this->client->sites()->list(offset: 0, limit: 1);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data['limit'])->toBe(1);
})->group('integration');
