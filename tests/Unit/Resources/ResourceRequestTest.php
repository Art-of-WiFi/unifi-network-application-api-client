<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Devices\DeviceFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Hotspot\VoucherFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Sites\SitesFilter;
use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiClient;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

/*
 * Sends every resource method through a mocked HTTP client and asserts the request that would hit
 * the wire: HTTP method, path, query string and JSON body, as defined by the OpenAPI specification.
 */

const MOCK_SITE_ID = '88f7af54-98f8-306a-a1c7-c9349722b1f6';
const MOCK_ITEM_ID = '550e8400-e29b-41d4-a716-446655440000';
const MOCK_ZONE_ID = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
const MOCK_BASE_URL = 'https://192.168.1.1/proxy/network/integration';

/**
 * Create a client with a mocked HTTP layer and the given site context
 */
function mockedClient(MockClient $mockClient, ?string $siteId = MOCK_SITE_ID): UnifiClient
{
    $client = new UnifiClient('https://192.168.1.1', 'test-key');
    $client->getConnector()->withMockClient($mockClient);

    if ($siteId !== null) {
        $client->setSiteId($siteId);
    }

    return $client;
}

dataset('resource requests', function () {
    $site = '/v1/sites/'.MOCK_SITE_ID;
    $id = MOCK_ITEM_ID;
    $page = ['offset' => 50, 'limit' => 10, 'filter' => "name.like('x*')"];

    return [
        // Application info & sites (global)
        'applicationInfo get' => [fn ($c) => $c->applicationInfo()->get(), Method::GET, '/v1/info', [], null],
        'sites list' => [fn ($c) => $c->sites()->list(50, 10, "name.like('x*')"), Method::GET, '/v1/sites', $page, null],
        'sites list with Filter object' => [fn ($c) => $c->sites()->list(filter: SitesFilter::id()->equals($id)), Method::GET, '/v1/sites', ['filter' => "id.eq({$id})"], null],
        'sites all' => [fn ($c) => $c->sites()->all(), Method::GET, '/v1/sites', [], null],

        // Devices
        'devices listAdopted' => [fn ($c) => $c->devices()->listAdopted(50, 10, "name.like('x*')"), Method::GET, "{$site}/devices", $page, null],
        'devices listPending' => [fn ($c) => $c->devices()->listPending(filter: DeviceFilter::supported()->equals(true)), Method::GET, '/v1/pending-devices', ['filter' => 'supported.eq(true)'], null],
        'devices get' => [fn ($c) => $c->devices()->get($id), Method::GET, "{$site}/devices/{$id}", [], null],
        'devices getStatistics' => [fn ($c) => $c->devices()->getStatistics($id), Method::GET, "{$site}/devices/{$id}/statistics/latest", [], null],
        'devices executeAction' => [fn ($c) => $c->devices()->executeAction($id, ['action' => 'RESTART']), Method::POST, "{$site}/devices/{$id}/actions", [], ['action' => 'RESTART']],
        'devices executePortAction' => [fn ($c) => $c->devices()->executePortAction($id, 3, ['action' => 'POWER_CYCLE']), Method::POST, "{$site}/devices/{$id}/interfaces/ports/3/actions", [], ['action' => 'POWER_CYCLE']],
        'devices adopt' => [fn ($c) => $c->devices()->adopt('94:2a:6f:26:c6:ca', true), Method::POST, "{$site}/devices", [], ['macAddress' => '94:2a:6f:26:c6:ca', 'ignoreDeviceLimit' => true]],
        'devices adopt default' => [fn ($c) => $c->devices()->adopt('94:2a:6f:26:c6:ca'), Method::POST, "{$site}/devices", [], ['macAddress' => '94:2a:6f:26:c6:ca', 'ignoreDeviceLimit' => false]],
        'devices remove' => [fn ($c) => $c->devices()->remove($id), Method::DELETE, "{$site}/devices/{$id}", [], null],

        // Clients
        'clients list' => [fn ($c) => $c->clients()->list(50, 10, "name.like('x*')"), Method::GET, "{$site}/clients", $page, null],
        'clients get' => [fn ($c) => $c->clients()->get($id), Method::GET, "{$site}/clients/{$id}", [], null],
        'clients executeAction' => [fn ($c) => $c->clients()->executeAction($id, ['action' => 'AUTHORIZE_GUEST_ACCESS', 'timeLimitMinutes' => 60]), Method::POST, "{$site}/clients/{$id}/actions", [], ['action' => 'AUTHORIZE_GUEST_ACCESS', 'timeLimitMinutes' => 60]],

        // Networks
        'networks list' => [fn ($c) => $c->networks()->list(50, 10, "name.like('x*')"), Method::GET, "{$site}/networks", $page, null],
        'networks get' => [fn ($c) => $c->networks()->get($id), Method::GET, "{$site}/networks/{$id}", [], null],
        'networks getReferences' => [fn ($c) => $c->networks()->getReferences($id), Method::GET, "{$site}/networks/{$id}/references", [], null],
        'networks create' => [fn ($c) => $c->networks()->create(['name' => 'IoT']), Method::POST, "{$site}/networks", [], ['name' => 'IoT']],
        'networks update' => [fn ($c) => $c->networks()->update($id, ['name' => 'IoT']), Method::PUT, "{$site}/networks/{$id}", [], ['name' => 'IoT']],
        'networks delete' => [fn ($c) => $c->networks()->delete($id), Method::DELETE, "{$site}/networks/{$id}", [], null],
        'networks delete force' => [fn ($c) => $c->networks()->delete($id, true), Method::DELETE, "{$site}/networks/{$id}", ['force' => 'true'], null],

        // WiFi broadcasts
        'wifiBroadcasts list' => [fn ($c) => $c->wifiBroadcasts()->list(50, 10, "name.like('x*')"), Method::GET, "{$site}/wifi/broadcasts", $page, null],
        'wifiBroadcasts get' => [fn ($c) => $c->wifiBroadcasts()->get($id), Method::GET, "{$site}/wifi/broadcasts/{$id}", [], null],
        'wifiBroadcasts create' => [fn ($c) => $c->wifiBroadcasts()->create(['name' => 'Guest', 'broadcastingFrequenciesGHz' => [2.4, 5]]), Method::POST, "{$site}/wifi/broadcasts", [], ['name' => 'Guest', 'broadcastingFrequenciesGHz' => [2.4, 5]]],
        'wifiBroadcasts update' => [fn ($c) => $c->wifiBroadcasts()->update($id, ['name' => 'Guest']), Method::PUT, "{$site}/wifi/broadcasts/{$id}", [], ['name' => 'Guest']],
        'wifiBroadcasts delete force' => [fn ($c) => $c->wifiBroadcasts()->delete($id, true), Method::DELETE, "{$site}/wifi/broadcasts/{$id}", ['force' => 'true'], null],

        // Hotspot
        'hotspot listVouchers' => [fn ($c) => $c->hotspot()->listVouchers(50, 10, "name.like('x*')"), Method::GET, "{$site}/hotspot/vouchers", $page, null],
        'hotspot getVoucher' => [fn ($c) => $c->hotspot()->getVoucher($id), Method::GET, "{$site}/hotspot/vouchers/{$id}", [], null],
        'hotspot createVouchers' => [fn ($c) => $c->hotspot()->createVouchers(['count' => 5, 'name' => 'Event', 'timeLimitMinutes' => 60]), Method::POST, "{$site}/hotspot/vouchers", [], ['count' => 5, 'name' => 'Event', 'timeLimitMinutes' => 60]],
        'hotspot deleteVoucher' => [fn ($c) => $c->hotspot()->deleteVoucher($id), Method::DELETE, "{$site}/hotspot/vouchers/{$id}", [], null],
        'hotspot deleteVouchers string' => [fn ($c) => $c->hotspot()->deleteVouchers('expired.eq(true)'), Method::DELETE, "{$site}/hotspot/vouchers", ['filter' => 'expired.eq(true)'], null],
        'hotspot deleteVouchers Filter' => [fn ($c) => $c->hotspot()->deleteVouchers(VoucherFilter::expiredOnly()), Method::DELETE, "{$site}/hotspot/vouchers", ['filter' => 'expired.eq(true)'], null],

        // Firewall zones
        'firewall listZones' => [fn ($c) => $c->firewall()->listZones(50, 10, "name.like('x*')"), Method::GET, "{$site}/firewall/zones", $page, null],
        'firewall getZone' => [fn ($c) => $c->firewall()->getZone($id), Method::GET, "{$site}/firewall/zones/{$id}", [], null],
        'firewall createZone' => [fn ($c) => $c->firewall()->createZone(['name' => 'DMZ', 'networkIds' => []]), Method::POST, "{$site}/firewall/zones", [], ['name' => 'DMZ', 'networkIds' => []]],
        'firewall updateZone' => [fn ($c) => $c->firewall()->updateZone($id, ['name' => 'DMZ', 'networkIds' => [$id]]), Method::PUT, "{$site}/firewall/zones/{$id}", [], ['name' => 'DMZ', 'networkIds' => [$id]]],
        'firewall deleteZone' => [fn ($c) => $c->firewall()->deleteZone($id), Method::DELETE, "{$site}/firewall/zones/{$id}", [], null],

        // Firewall policies
        'firewall listPolicies' => [fn ($c) => $c->firewall()->listPolicies(50, 10, "name.like('x*')"), Method::GET, "{$site}/firewall/policies", $page, null],
        'firewall getPolicy' => [fn ($c) => $c->firewall()->getPolicy($id), Method::GET, "{$site}/firewall/policies/{$id}", [], null],
        'firewall createPolicy' => [fn ($c) => $c->firewall()->createPolicy(['name' => 'Block', 'action' => ['type' => 'BLOCK']]), Method::POST, "{$site}/firewall/policies", [], ['name' => 'Block', 'action' => ['type' => 'BLOCK']]],
        'firewall updatePolicy' => [fn ($c) => $c->firewall()->updatePolicy($id, ['name' => 'Block']), Method::PUT, "{$site}/firewall/policies/{$id}", [], ['name' => 'Block']],
        'firewall patchPolicy' => [fn ($c) => $c->firewall()->patchPolicy($id, ['loggingEnabled' => true]), Method::PATCH, "{$site}/firewall/policies/{$id}", [], ['loggingEnabled' => true]],
        'firewall deletePolicy' => [fn ($c) => $c->firewall()->deletePolicy($id), Method::DELETE, "{$site}/firewall/policies/{$id}", [], null],
        'firewall getPolicyOrdering' => [fn ($c) => $c->firewall()->getPolicyOrdering(MOCK_ZONE_ID, $id), Method::GET, "{$site}/firewall/policies/ordering", ['sourceFirewallZoneId' => MOCK_ZONE_ID, 'destinationFirewallZoneId' => $id], null],
        'firewall updatePolicyOrdering' => [
            fn ($c) => $c->firewall()->updatePolicyOrdering(MOCK_ZONE_ID, $id, ['orderedFirewallPolicyIds' => ['beforeSystemDefined' => [$id], 'afterSystemDefined' => []]]),
            Method::PUT,
            "{$site}/firewall/policies/ordering",
            ['sourceFirewallZoneId' => MOCK_ZONE_ID, 'destinationFirewallZoneId' => $id],
            ['orderedFirewallPolicyIds' => ['beforeSystemDefined' => [$id], 'afterSystemDefined' => []]],
        ],

        // ACL rules
        'aclRules list' => [fn ($c) => $c->aclRules()->list(50, 10, "name.like('x*')"), Method::GET, "{$site}/acl-rules", $page, null],
        'aclRules get' => [fn ($c) => $c->aclRules()->get($id), Method::GET, "{$site}/acl-rules/{$id}", [], null],
        'aclRules create' => [fn ($c) => $c->aclRules()->create(['type' => 'IPV4', 'name' => 'Rule']), Method::POST, "{$site}/acl-rules", [], ['type' => 'IPV4', 'name' => 'Rule']],
        'aclRules update' => [fn ($c) => $c->aclRules()->update($id, ['type' => 'IPV4', 'name' => 'Rule']), Method::PUT, "{$site}/acl-rules/{$id}", [], ['type' => 'IPV4', 'name' => 'Rule']],
        'aclRules delete' => [fn ($c) => $c->aclRules()->delete($id), Method::DELETE, "{$site}/acl-rules/{$id}", [], null],
        'aclRules getOrdering' => [fn ($c) => $c->aclRules()->getOrdering(), Method::GET, "{$site}/acl-rules/ordering", [], null],
        'aclRules updateOrdering' => [fn ($c) => $c->aclRules()->updateOrdering(['orderedAclRuleIds' => [$id]]), Method::PUT, "{$site}/acl-rules/ordering", [], ['orderedAclRuleIds' => [$id]]],

        // DNS policies
        'dnsPolicies list' => [fn ($c) => $c->dnsPolicies()->list(50, 10, "name.like('x*')"), Method::GET, "{$site}/dns/policies", $page, null],
        'dnsPolicies get' => [fn ($c) => $c->dnsPolicies()->get($id), Method::GET, "{$site}/dns/policies/{$id}", [], null],
        'dnsPolicies create' => [fn ($c) => $c->dnsPolicies()->create(['type' => 'A_RECORD', 'domain' => 'a.local']), Method::POST, "{$site}/dns/policies", [], ['type' => 'A_RECORD', 'domain' => 'a.local']],
        'dnsPolicies update' => [fn ($c) => $c->dnsPolicies()->update($id, ['type' => 'A_RECORD', 'domain' => 'a.local']), Method::PUT, "{$site}/dns/policies/{$id}", [], ['type' => 'A_RECORD', 'domain' => 'a.local']],
        'dnsPolicies delete' => [fn ($c) => $c->dnsPolicies()->delete($id), Method::DELETE, "{$site}/dns/policies/{$id}", [], null],

        // Traffic matching lists
        'trafficMatchingLists list' => [fn ($c) => $c->trafficMatchingLists()->list(50, 10, "name.like('x*')"), Method::GET, "{$site}/traffic-matching-lists", $page, null],
        'trafficMatchingLists get' => [fn ($c) => $c->trafficMatchingLists()->get($id), Method::GET, "{$site}/traffic-matching-lists/{$id}", [], null],
        'trafficMatchingLists create' => [fn ($c) => $c->trafficMatchingLists()->create(['type' => 'PORTS', 'name' => 'Web']), Method::POST, "{$site}/traffic-matching-lists", [], ['type' => 'PORTS', 'name' => 'Web']],
        'trafficMatchingLists update' => [fn ($c) => $c->trafficMatchingLists()->update($id, ['type' => 'PORTS', 'name' => 'Web']), Method::PUT, "{$site}/traffic-matching-lists/{$id}", [], ['type' => 'PORTS', 'name' => 'Web']],
        'trafficMatchingLists delete' => [fn ($c) => $c->trafficMatchingLists()->delete($id), Method::DELETE, "{$site}/traffic-matching-lists/{$id}", [], null],

        // Switching
        'switching listSwitchStacks' => [fn ($c) => $c->switching()->listSwitchStacks(50, 10, "name.like('x*')"), Method::GET, "{$site}/switching/switch-stacks", $page, null],
        'switching getSwitchStack' => [fn ($c) => $c->switching()->getSwitchStack($id), Method::GET, "{$site}/switching/switch-stacks/{$id}", [], null],
        'switching listMcLagDomains' => [fn ($c) => $c->switching()->listMcLagDomains(50, 10, "name.like('x*')"), Method::GET, "{$site}/switching/mc-lag-domains", $page, null],
        'switching getMcLagDomain' => [fn ($c) => $c->switching()->getMcLagDomain($id), Method::GET, "{$site}/switching/mc-lag-domains/{$id}", [], null],
        'switching listLags' => [fn ($c) => $c->switching()->listLags(50, 10, "name.like('x*')"), Method::GET, "{$site}/switching/lags", $page, null],
        'switching getLag' => [fn ($c) => $c->switching()->getLag($id), Method::GET, "{$site}/switching/lags/{$id}", [], null],

        // Supporting resources
        'supportingResources listWanInterfaces' => [fn ($c) => $c->supportingResources()->listWanInterfaces(50, 10), Method::GET, "{$site}/wans", ['offset' => 50, 'limit' => 10], null],
        'supportingResources listSiteToSiteVpnTunnels' => [fn ($c) => $c->supportingResources()->listSiteToSiteVpnTunnels(50, 10, "name.like('x*')"), Method::GET, "{$site}/vpn/site-to-site-tunnels", $page, null],
        'supportingResources listVpnServers' => [fn ($c) => $c->supportingResources()->listVpnServers(50, 10, "name.like('x*')"), Method::GET, "{$site}/vpn/servers", $page, null],
        'supportingResources listRadiusProfiles' => [fn ($c) => $c->supportingResources()->listRadiusProfiles(50, 10, "name.like('x*')"), Method::GET, "{$site}/radius/profiles", $page, null],
        'supportingResources listDeviceTags' => [fn ($c) => $c->supportingResources()->listDeviceTags(50, 10, "name.like('x*')"), Method::GET, "{$site}/device-tags", $page, null],
        'supportingResources listDpiCategories' => [fn ($c) => $c->supportingResources()->listDpiCategories(50, 10, "name.like('x*')"), Method::GET, '/v1/dpi/categories', $page, null],
        'supportingResources listDpiApplications' => [fn ($c) => $c->supportingResources()->listDpiApplications(50, 10, "name.like('x*')"), Method::GET, '/v1/dpi/applications', $page, null],
        'supportingResources listCountries' => [fn ($c) => $c->supportingResources()->listCountries(50, 10, "name.like('x*')"), Method::GET, '/v1/countries', $page, null],
    ];
});

test('sends the expected request', function (Closure $call, Method $method, string $path, array $query, ?array $body) {
    $mockClient = new MockClient([MockResponse::make(['data' => []])]);

    $call(mockedClient($mockClient));

    $request = $mockClient->getLastPendingRequest();

    expect($request->getMethod())->toBe($method);
    expect(strtok($request->getUrl(), '?'))->toBe(MOCK_BASE_URL.$path);
    expect($request->query()->all())->toBe($query);

    if ($body === null) {
        expect($request->body()?->all() ?? [])->toBe([]);
    } else {
        expect($request->body()->all())->toBe($body);
        expect($request->headers()->get('Content-Type'))->toBe('application/json');
    }

    expect($request->headers()->get('X-API-KEY'))->toBe('test-key');
})->with('resource requests');

test('list methods omit pagination and filter when not given', function () {
    $mockClient = new MockClient([MockResponse::make(['data' => []])]);

    mockedClient($mockClient)->devices()->listAdopted();

    expect($mockClient->getLastPendingRequest()->query()->all())->toBe([]);
});

test('site-scoped methods throw before sending when no site ID is set', function (Closure $call) {
    $mockClient = new MockClient([MockResponse::make(['data' => []])]);

    expect(fn () => $call(mockedClient($mockClient, siteId: null)))
        ->toThrow(RuntimeException::class);

    $mockClient->assertNothingSent();
})->with([
    'devices listAdopted' => [fn ($c) => $c->devices()->listAdopted()],
    'clients get' => [fn ($c) => $c->clients()->get(MOCK_ITEM_ID)],
    'networks create' => [fn ($c) => $c->networks()->create([])],
    'hotspot deleteVouchers' => [fn ($c) => $c->hotspot()->deleteVouchers('expired.eq(true)')],
    'firewall patchPolicy' => [fn ($c) => $c->firewall()->patchPolicy(MOCK_ITEM_ID, [])],
    'switching listLags' => [fn ($c) => $c->switching()->listLags()],
    'supportingResources listWanInterfaces' => [fn ($c) => $c->supportingResources()->listWanInterfaces()],
]);

test('global endpoints work without a site ID', function (Closure $call) {
    $mockClient = new MockClient([MockResponse::make(['data' => []])]);

    $call(mockedClient($mockClient, siteId: null));

    $mockClient->assertSentCount(1);
})->with([
    'applicationInfo get' => [fn ($c) => $c->applicationInfo()->get()],
    'sites list' => [fn ($c) => $c->sites()->list()],
    'devices listPending' => [fn ($c) => $c->devices()->listPending()],
    'supportingResources listCountries' => [fn ($c) => $c->supportingResources()->listCountries()],
    'supportingResources listDpiCategories' => [fn ($c) => $c->supportingResources()->listDpiCategories()],
    'supportingResources listDpiApplications' => [fn ($c) => $c->supportingResources()->listDpiApplications()],
]);
