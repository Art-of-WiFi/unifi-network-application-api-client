<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can get application info', function () {
    $response = $this->client->applicationInfo()->get();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)->toBeArray();
})->group('integration');
