<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\LagFilter;

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
});

test('can list switch stacks', function () {
    $response = $this->client->switching()->listSwitchStacks();

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

test('can get switch stack details', function () {
    $list = $this->client->switching()->listSwitchStacks(limit: 1)->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No switch stacks available for testing.');
    }

    $switchStackId = $list['data'][0]['id'];
    $response = $this->client->switching()->getSwitchStack($switchStackId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $switchStackId)
        ->toHaveKey('units')
        ->toHaveKey('lags');
    // Single-item fetch should NOT have a 'data' wrapper
    expect($data)->not->toHaveKey('data');
    expect($data)->not->toHaveKey('offset');
})->group('integration');

test('can list MC-LAG domains', function () {
    $response = $this->client->switching()->listMcLagDomains();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data')
        ->toHaveKey('totalCount');
    expect($data['data'])->toBeArray();
})->group('integration');

test('can get MC-LAG domain details', function () {
    $list = $this->client->switching()->listMcLagDomains(limit: 1)->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No MC-LAG domains available for testing.');
    }

    $mcLagDomainId = $list['data'][0]['id'];
    $response = $this->client->switching()->getMcLagDomain($mcLagDomainId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $mcLagDomainId)
        ->toHaveKey('peers')
        ->toHaveKey('lags');
    expect($data)->not->toHaveKey('data');
    expect($data)->not->toHaveKey('offset');
})->group('integration');

test('can list LAGs', function () {
    $response = $this->client->switching()->listLags();

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('data')
        ->toHaveKey('totalCount');
    expect($data['data'])->toBeArray();

    foreach ($data['data'] as $lag) {
        expect($lag['type'])->toBeIn(['LOCAL', 'SWITCH_STACK', 'MULTI_CHASSIS']);
    }
})->group('integration');

test('can list LAGs with a filter', function () {
    $response = $this->client->switching()->listLags(filter: LagFilter::localLags());

    expect($response->successful())->toBeTrue();

    foreach ($response->json('data') as $lag) {
        expect($lag['type'])->toBe('LOCAL');
    }
})->group('integration');

test('can get LAG details', function () {
    $list = $this->client->switching()->listLags(limit: 1)->json();

    if (empty($list['data'])) {
        $this->markTestSkipped('No LAGs available for testing.');
    }

    $lagId = $list['data'][0]['id'];
    $response = $this->client->switching()->getLag($lagId);

    expect($response->successful())->toBeTrue();

    $data = $response->json();
    expect($data)
        ->toBeArray()
        ->toHaveKey('id', $lagId)
        ->toHaveKey('type')
        ->toHaveKey('members');
    expect($data)->not->toHaveKey('data');
    expect($data)->not->toHaveKey('offset');
})->group('integration');
