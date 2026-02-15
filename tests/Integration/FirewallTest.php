<?php

declare(strict_types=1);

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

// Zones

test('can list firewall zones', function () {
    $response = $this->client->firewall()->listZones();

    // Zone-based firewall may not be configured on the controller
    if ($response->status() === 400) {
        $body = $response->json();
        if (($body['code'] ?? '') === 'api.firewall.zone-based-firewall-not-configured') {
            $this->markTestSkipped('Zone-based firewall is not configured on this controller.');
        }
    }

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

test('can get firewall zone details', function () {
    $listResponse = $this->client->firewall()->listZones(limit: 1);

    if ($listResponse->status() === 400) {
        $this->markTestSkipped('Zone-based firewall is not configured on this controller.');
    }

    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No firewall zones available for testing.');
    }

    $zoneId = $list['data'][0]['id'];
    $response = $this->client->firewall()->getZone($zoneId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $zoneId);
    expect($data)->not->toHaveKey('offset');
    expect($data)->not->toHaveKey('limit');
})->group('integration');

// Policies

test('can list firewall policies', function () {
    $response = $this->client->firewall()->listPolicies();

    // Zone-based firewall may not be configured on the controller
    if ($response->status() === 400) {
        $body = $response->json();
        if (($body['code'] ?? '') === 'api.firewall.zone-based-firewall-not-configured') {
            $this->markTestSkipped('Zone-based firewall is not configured on this controller.');
        }
    }

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data')
        ->toHaveKey('offset')
        ->toHaveKey('limit')
        ->toHaveKey('totalCount');
})->group('integration');

test('can get firewall policy details', function () {
    $listResponse = $this->client->firewall()->listPolicies(limit: 1);

    if ($listResponse->status() === 400) {
        $this->markTestSkipped('Zone-based firewall is not configured on this controller.');
    }

    $list = $listResponse->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No firewall policies available for testing.');
    }

    $policyId = $list['data'][0]['id'];
    $response = $this->client->firewall()->getPolicy($policyId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $policyId);
    expect($data)->not->toHaveKey('offset');
    expect($data)->not->toHaveKey('limit');
})->group('integration');

test('can get firewall policy ordering', function () {
    $zonesResponse = $this->client->firewall()->listZones(limit: 2);

    if ($zonesResponse->status() === 400) {
        $this->markTestSkipped('Zone-based firewall is not configured on this controller.');
    }

    $zones = $zonesResponse->json();

    if (count($zones['data'] ?? []) < 2) {
        $this->markTestSkipped('Need at least 2 firewall zones to test policy ordering.');
    }

    $sourceZoneId = $zones['data'][0]['id'];
    $destZoneId = $zones['data'][1]['id'];

    $response = $this->client->firewall()->getPolicyOrdering($sourceZoneId, $destZoneId);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toBeArray();
})->group('integration');
