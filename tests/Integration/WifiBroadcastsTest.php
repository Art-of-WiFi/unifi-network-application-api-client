<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list wifi broadcasts', function () {
    $response = $this->client->wifiBroadcasts()->list();

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

test('can get wifi broadcast details', function () {
    $listResponse = $this->client->wifiBroadcasts()->list(limit: 1);
    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No WiFi broadcasts available for testing.');
    }

    $broadcastId = $list['data'][0]['id'];
    $response = $this->client->wifiBroadcasts()->get($broadcastId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $broadcastId);
    expect($data)->not->toHaveKey('offset');
    expect($data)->not->toHaveKey('limit');
})->group('integration');
