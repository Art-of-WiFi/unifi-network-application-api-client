<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\AclRulesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\ApplicationInfoResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\ClientsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\DevicesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\DnsPoliciesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\FirewallResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\HotspotResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\NetworksResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SitesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SupportingResourcesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\TrafficMatchingListsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\WifiBroadcastsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;
use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiConnector;

beforeEach(function () {
    $this->client = new UnifiClient('https://192.168.1.1', 'test-api-key', false);
});

test('client can be instantiated', function () {
    expect($this->client)->toBeInstanceOf(UnifiClient::class);
});

test('getConnector() returns UnifiConnector', function () {
    expect($this->client->getConnector())->toBeInstanceOf(UnifiConnector::class);
});

test('getSiteId() returns null by default', function () {
    expect($this->client->getSiteId())->toBeNull();
});

test('setSiteId() sets and returns the site ID', function () {
    $siteId = '550e8400-e29b-41d4-a716-446655440000';
    $result = $this->client->setSiteId($siteId);

    expect($result)->toBe($this->client);
    expect($this->client->getSiteId())->toBe($siteId);
});

test('setSiteId() is chainable', function () {
    expect($this->client->setSiteId('abc'))->toBeInstanceOf(UnifiClient::class);
});

// Resource factory methods

test('applicationInfo() returns ApplicationInfoResource', function () {
    expect($this->client->applicationInfo())->toBeInstanceOf(ApplicationInfoResource::class);
});

test('sites() returns SitesResource', function () {
    expect($this->client->sites())->toBeInstanceOf(SitesResource::class);
});

test('devices() returns DevicesResource', function () {
    expect($this->client->devices())->toBeInstanceOf(DevicesResource::class);
});

test('clients() returns ClientsResource', function () {
    expect($this->client->clients())->toBeInstanceOf(ClientsResource::class);
});

test('networks() returns NetworksResource', function () {
    expect($this->client->networks())->toBeInstanceOf(NetworksResource::class);
});

test('wifiBroadcasts() returns WifiBroadcastsResource', function () {
    expect($this->client->wifiBroadcasts())->toBeInstanceOf(WifiBroadcastsResource::class);
});

test('hotspot() returns HotspotResource', function () {
    expect($this->client->hotspot())->toBeInstanceOf(HotspotResource::class);
});

test('firewall() returns FirewallResource', function () {
    expect($this->client->firewall())->toBeInstanceOf(FirewallResource::class);
});

test('aclRules() returns AclRulesResource', function () {
    expect($this->client->aclRules())->toBeInstanceOf(AclRulesResource::class);
});

test('trafficMatchingLists() returns TrafficMatchingListsResource', function () {
    expect($this->client->trafficMatchingLists())->toBeInstanceOf(TrafficMatchingListsResource::class);
});

test('dnsPolicies() returns DnsPoliciesResource', function () {
    expect($this->client->dnsPolicies())->toBeInstanceOf(DnsPoliciesResource::class);
});

test('supportingResources() returns SupportingResourcesResource', function () {
    expect($this->client->supportingResources())->toBeInstanceOf(SupportingResourcesResource::class);
});
