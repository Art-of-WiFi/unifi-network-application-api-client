<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\CreateAclRuleRequest;
// Application Info
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\DeleteAclRuleRequest;
// Sites
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRuleOrderingRequest;
// Devices
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\GetAclRulesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\UpdateAclRuleOrderingRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules\UpdateAclRuleRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\ApplicationInfo\GetInfoRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\ExecuteClientActionRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\GetClientDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients\GetConnectedClientsRequest;
// Clients
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\AdoptDeviceRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\ExecuteDeviceActionRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\ExecutePortActionRequest;
// Networks
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetAdoptedDevicesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetDeviceDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetDeviceStatisticsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\GetPendingDevicesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices\RemoveDeviceRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\CreateDnsPolicyRequest;
// WiFi Broadcasts
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\DeleteDnsPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\GetDnsPoliciesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\GetDnsPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\UpdateDnsPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\CreateFirewallPolicyRequest;
// Hotspot
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\CreateFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\DeleteFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\DeleteFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallPoliciesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallPolicyOrderingRequest;
// Firewall
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallZonesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\PatchFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallPolicyOrderingRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\CreateVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\DeleteVoucherRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\DeleteVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\GetVoucherRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot\GetVouchersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\CreateNetworkRequest;
// ACL Rules
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\DeleteNetworkRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworkDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworkReferencesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworksRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\UpdateNetworkRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Sites\GetSitesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetCountriesRequest;
// Traffic Matching Lists
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetDeviceTagsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetDpiApplicationsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetDpiCategoriesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetRadiusProfilesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetSiteToSiteVpnTunnelsRequest;
// DNS Policies
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetVpnServersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetWanInterfacesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\CreateTrafficMatchingListRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\DeleteTrafficMatchingListRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\GetTrafficMatchingListRequest;
// Supporting Resources
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\GetTrafficMatchingListsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\UpdateTrafficMatchingListRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\CreateWifiBroadcastRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\DeleteWifiBroadcastRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\GetWifiBroadcastDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\GetWifiBroadcastsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\UpdateWifiBroadcastRequest;
use Saloon\Enums\Method;

$siteId = 'site-uuid-123';
$itemId = 'item-uuid-456';

// ============================================================
// Application Info
// ============================================================

test('GetInfoRequest resolves correct endpoint', function () {
    $request = new GetInfoRequest;
    expect($request->resolveEndpoint())->toBe('/v1/info');
    expect($request->getMethod())->toBe(Method::GET);
});

// ============================================================
// Sites
// ============================================================

test('GetSitesRequest resolves correct endpoint', function () {
    $request = new GetSitesRequest;
    expect($request->resolveEndpoint())->toBe('/v1/sites');
    expect($request->getMethod())->toBe(Method::GET);
});

// ============================================================
// Devices
// ============================================================

test('GetAdoptedDevicesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetAdoptedDevicesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetPendingDevicesRequest resolves correct endpoint', function () {
    $request = new GetPendingDevicesRequest;
    expect($request->resolveEndpoint())->toBe('/v1/pending-devices');
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetDeviceDetailsRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetDeviceDetailsRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetDeviceStatisticsRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetDeviceStatisticsRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices/{$itemId}/statistics/latest");
    expect($request->getMethod())->toBe(Method::GET);
});

test('AdoptDeviceRequest resolves correct endpoint', function () use ($siteId) {
    $request = new AdoptDeviceRequest($siteId, 'AA:BB:CC:DD:EE:FF');
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices");
    expect($request->getMethod())->toBe(Method::POST);
});

test('RemoveDeviceRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new RemoveDeviceRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

test('ExecuteDeviceActionRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new ExecuteDeviceActionRequest($siteId, $itemId, ['action' => 'RESTART']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices/{$itemId}/actions");
    expect($request->getMethod())->toBe(Method::POST);
});

test('ExecutePortActionRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new ExecutePortActionRequest($siteId, $itemId, 0, ['action' => 'ENABLE']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/devices/{$itemId}/interfaces/ports/0/actions");
    expect($request->getMethod())->toBe(Method::POST);
});

// ============================================================
// Clients
// ============================================================

test('GetConnectedClientsRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetConnectedClientsRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/clients");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetClientDetailsRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetClientDetailsRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/clients/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('ExecuteClientActionRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new ExecuteClientActionRequest($siteId, $itemId, ['action' => 'AUTHORIZE_GUEST_ACCESS']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/clients/{$itemId}/actions");
    expect($request->getMethod())->toBe(Method::POST);
});

// ============================================================
// Networks
// ============================================================

test('GetNetworksRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetNetworksRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/networks");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetNetworkDetailsRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetNetworkDetailsRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/networks/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetNetworkReferencesRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetNetworkReferencesRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/networks/{$itemId}/references");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateNetworkRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateNetworkRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/networks");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateNetworkRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateNetworkRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/networks/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('DeleteNetworkRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteNetworkRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/networks/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

// ============================================================
// WiFi Broadcasts
// ============================================================

test('GetWifiBroadcastsRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetWifiBroadcastsRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/wifi/broadcasts");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetWifiBroadcastDetailsRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetWifiBroadcastDetailsRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/wifi/broadcasts/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateWifiBroadcastRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateWifiBroadcastRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/wifi/broadcasts");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateWifiBroadcastRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateWifiBroadcastRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/wifi/broadcasts/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('DeleteWifiBroadcastRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteWifiBroadcastRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/wifi/broadcasts/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

// ============================================================
// Hotspot
// ============================================================

test('GetVouchersRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetVouchersRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/hotspot/vouchers");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetVoucherRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetVoucherRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/hotspot/vouchers/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateVouchersRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateVouchersRequest($siteId, []);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/hotspot/vouchers");
    expect($request->getMethod())->toBe(Method::POST);
});

test('DeleteVoucherRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteVoucherRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/hotspot/vouchers/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

test('DeleteVouchersRequest resolves correct endpoint', function () use ($siteId) {
    $request = new DeleteVouchersRequest($siteId, 'batch-id-123');
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/hotspot/vouchers");
    expect($request->getMethod())->toBe(Method::DELETE);
});

// ============================================================
// Firewall
// ============================================================

test('GetFirewallZonesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetFirewallZonesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/zones");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetFirewallZoneRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetFirewallZoneRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/zones/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateFirewallZoneRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateFirewallZoneRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/zones");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateFirewallZoneRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateFirewallZoneRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/zones/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('DeleteFirewallZoneRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteFirewallZoneRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/zones/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

test('GetFirewallPoliciesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetFirewallPoliciesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetFirewallPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetFirewallPolicyRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateFirewallPolicyRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateFirewallPolicyRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateFirewallPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateFirewallPolicyRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('PatchFirewallPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new PatchFirewallPolicyRequest($siteId, $itemId, ['enabled' => true]);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::PATCH);
});

test('DeleteFirewallPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteFirewallPolicyRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

test('GetFirewallPolicyOrderingRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetFirewallPolicyOrderingRequest($siteId, 'src-zone-id', 'dst-zone-id');
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies/ordering");
    expect($request->getMethod())->toBe(Method::GET);
});

test('UpdateFirewallPolicyOrderingRequest resolves correct endpoint', function () use ($siteId) {
    $request = new UpdateFirewallPolicyOrderingRequest($siteId, 'src-zone-id', 'dst-zone-id', ['policyIds' => []]);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/firewall/policies/ordering");
    expect($request->getMethod())->toBe(Method::PUT);
});

// ============================================================
// ACL Rules
// ============================================================

test('GetAclRulesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetAclRulesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetAclRuleRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetAclRuleRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateAclRuleRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateAclRuleRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateAclRuleRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateAclRuleRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('DeleteAclRuleRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteAclRuleRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

test('GetAclRuleOrderingRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetAclRuleOrderingRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules/ordering");
    expect($request->getMethod())->toBe(Method::GET);
});

test('UpdateAclRuleOrderingRequest resolves correct endpoint', function () use ($siteId) {
    $request = new UpdateAclRuleOrderingRequest($siteId, ['ruleIds' => []]);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/acl-rules/ordering");
    expect($request->getMethod())->toBe(Method::PUT);
});

// ============================================================
// Traffic Matching Lists
// ============================================================

test('GetTrafficMatchingListsRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetTrafficMatchingListsRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/traffic-matching-lists");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetTrafficMatchingListRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetTrafficMatchingListRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/traffic-matching-lists/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateTrafficMatchingListRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateTrafficMatchingListRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/traffic-matching-lists");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateTrafficMatchingListRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateTrafficMatchingListRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/traffic-matching-lists/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('DeleteTrafficMatchingListRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteTrafficMatchingListRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/traffic-matching-lists/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

// ============================================================
// DNS Policies
// ============================================================

test('GetDnsPoliciesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetDnsPoliciesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/dns/policies");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetDnsPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new GetDnsPolicyRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/dns/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::GET);
});

test('CreateDnsPolicyRequest resolves correct endpoint', function () use ($siteId) {
    $request = new CreateDnsPolicyRequest($siteId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/dns/policies");
    expect($request->getMethod())->toBe(Method::POST);
});

test('UpdateDnsPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new UpdateDnsPolicyRequest($siteId, $itemId, ['name' => 'Test']);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/dns/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::PUT);
});

test('DeleteDnsPolicyRequest resolves correct endpoint', function () use ($siteId, $itemId) {
    $request = new DeleteDnsPolicyRequest($siteId, $itemId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/dns/policies/{$itemId}");
    expect($request->getMethod())->toBe(Method::DELETE);
});

// ============================================================
// Supporting Resources
// ============================================================

test('GetCountriesRequest resolves correct endpoint', function () {
    $request = new GetCountriesRequest;
    expect($request->resolveEndpoint())->toBe('/v1/countries');
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetDeviceTagsRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetDeviceTagsRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/device-tags");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetDpiApplicationsRequest resolves correct endpoint', function () {
    $request = new GetDpiApplicationsRequest;
    expect($request->resolveEndpoint())->toBe('/v1/dpi/applications');
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetDpiCategoriesRequest resolves correct endpoint', function () {
    $request = new GetDpiCategoriesRequest;
    expect($request->resolveEndpoint())->toBe('/v1/dpi/categories');
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetRadiusProfilesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetRadiusProfilesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/radius/profiles");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetSiteToSiteVpnTunnelsRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetSiteToSiteVpnTunnelsRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/vpn/site-to-site-tunnels");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetVpnServersRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetVpnServersRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/vpn/servers");
    expect($request->getMethod())->toBe(Method::GET);
});

test('GetWanInterfacesRequest resolves correct endpoint', function () use ($siteId) {
    $request = new GetWanInterfacesRequest($siteId);
    expect($request->resolveEndpoint())->toBe("/v1/sites/{$siteId}/wans");
    expect($request->getMethod())->toBe(Method::GET);
});
