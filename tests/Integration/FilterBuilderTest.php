<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\AclRules\AclRuleFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Clients\ClientFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Devices\DeviceFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\DnsPolicies\DnsPolicyFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Firewall\FirewallPolicyFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Firewall\FirewallZoneFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Hotspot\VoucherFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Networks\NetworkFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Sites\SitesFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources\DeviceTagsFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\LagFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\SwitchStackFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\WifiBroadcasts\WifiBroadcastFilter;

use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\createTestClient;
use function ArtOfWiFi\UnifiNetworkApplicationApi\Tests\skipIfNoController;

/*
 * These tests send filters produced by the filter builders to a real controller and assert
 * that the API accepts them. They guard against value formatting the API rejects with
 * 400 api.request.invalid-filter (e.g. quoted UUIDs, quoted timestamps, wrong quote escaping).
 * The results themselves are not asserted, only that the filter is accepted.
 */

beforeEach(function () {
    skipIfNoController();
    $this->client = createTestClient();
    $this->uuid = '550e8400-e29b-41d4-a716-446655440000';
});

test('the API accepts filters with UUID values', function () {
    expect($this->client->networks()->list(filter: NetworkFilter::id()->equals($this->uuid))->status())->toBe(200);
    expect($this->client->devices()->listAdopted(filter: DeviceFilter::id()->in([$this->uuid]))->status())->toBe(200);
    expect($this->client->supportingResources()->listDeviceTags(filter: DeviceTagsFilter::deviceIds()->contains($this->uuid))->status())->toBe(200);
    expect($this->client->sites()->list(filter: SitesFilter::id()->notEquals($this->uuid))->status())->toBe(200);
})->group('integration');

test('the API accepts filters with timestamp values', function () {
    $since = new DateTimeImmutable('-30 days');

    expect($this->client->clients()->list(filter: ClientFilter::connectedAt()->greaterThan($since))->status())->toBe(200);
    expect($this->client->hotspot()->listVouchers(filter: VoucherFilter::createdAt()->greaterThanOrEqual($since))->status())->toBe(200);
})->group('integration');

test('the API accepts filters with integer and decimal set values', function () {
    expect($this->client->wifiBroadcasts()->list(filter: WifiBroadcastFilter::broadcastingFrequenciesGHz()->contains(2.4))->status())->toBe(200);
    expect($this->client->switching()->listLags(filter: LagFilter::membersPortIdxs()->contains(1))->status())->toBe(200);
    expect($this->client->switching()->listSwitchStacks(filter: SwitchStackFilter::unitsId()->containsAny([1, 2]))->status())->toBe(200);
    expect($this->client->aclRules()->list(filter: AclRuleFilter::destinationPortFilter()->contains(443))->status())->toBe(200);
})->group('integration');

test('the API accepts filters with single quotes in string values', function () {
    expect($this->client->networks()->list(filter: NetworkFilter::name()->equals("Erik's Lab"))->status())->toBe(200);
    expect($this->client->dnsPolicies()->list(filter: DnsPolicyFilter::domain()->like("*'*"))->status())->toBe(200);
})->group('integration');

test('the API accepts newly added filter properties', function () {
    expect($this->client->aclRules()->list(filter: AclRuleFilter::and(
        AclRuleFilter::metadataOrigin()->equals('USER_DEFINED'),
        AclRuleFilter::sourceFilterType()->isNotNull(),
        AclRuleFilter::index()->greaterThanOrEqual(0)
    ))->status())->toBe(200);

    expect($this->client->wifiBroadcasts()->list(filter: WifiBroadcastFilter::and(
        WifiBroadcastFilter::securityType()->notEquals('OPEN'),
        WifiBroadcastFilter::networkType()->isNotNull(),
        WifiBroadcastFilter::broadcastingDeviceFilterType()->isNull()
    ))->status())->toBe(200);

    expect($this->client->hotspot()->listVouchers(filter: VoucherFilter::and(
        VoucherFilter::authorizedGuestCount()->equals(0),
        VoucherFilter::timeLimitMinutes()->greaterThan(0)
    ))->status())->toBe(200);
})->group('integration');

test('the API accepts zone and policy filters when zone-based firewall is configured', function () {
    $response = $this->client->firewall()->listZones(filter: FirewallZoneFilter::metadataConfigurable()->equals(true));

    if ($response->status() === 400 && $response->json('code') === 'api.firewall.zone-based-firewall-not-configured') {
        $this->markTestSkipped('Zone-based firewall is not configured on this controller.');
    }

    expect($response->status())->toBe(200);
    expect($this->client->firewall()->listPolicies(filter: FirewallPolicyFilter::sourceZoneId()->equals($this->uuid))->status())->toBe(200);
})->group('integration');
