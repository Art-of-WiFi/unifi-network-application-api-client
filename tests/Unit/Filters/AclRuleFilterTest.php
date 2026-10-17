<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\AclRules\AclRuleFilter;

test('id() sets id property', function () {
    expect(AclRuleFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(AclRuleFilter::name()->like('Block*')->toString())
        ->toBe("name.like('Block*')");
});

test('type() sets type property', function () {
    expect(AclRuleFilter::type()->equals('LAN')->toString())
        ->toBe("type.eq('LAN')");
});

test('enabled() sets enabled property', function () {
    expect(AclRuleFilter::enabled()->equals(true)->toString())
        ->toBe('enabled.eq(true)');
});

test('action() sets action property', function () {
    expect(AclRuleFilter::action()->equals('BLOCK')->toString())
        ->toBe("action.eq('BLOCK')");
});

// Preset filters

test('enabledOnly() preset', function () {
    expect(AclRuleFilter::enabledOnly()->toString())
        ->toBe('enabled.eq(true)');
});

test('disabledOnly() preset', function () {
    expect(AclRuleFilter::disabledOnly()->toString())
        ->toBe('enabled.eq(false)');
});

test('index() supports numeric comparisons', function () {
    expect(AclRuleFilter::index()->lessThan(100)->toString())
        ->toBe('index.lt(100)');
});

test('description() isNull', function () {
    expect(AclRuleFilter::description()->isNull()->toString())
        ->toBe('description.isNull()');
});

test('protocolFilter() contains', function () {
    expect(AclRuleFilter::protocolFilter()->contains('TCP')->toString())
        ->toBe("protocolFilter.contains('TCP')");
});

test('networkId() emits an unquoted UUID', function () {
    expect(AclRuleFilter::networkId()->equals('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('networkId.eq(550e8400-e29b-41d4-a716-446655440000)');
});

test('enforcingDeviceIds() sets enforcingDeviceFilter.deviceIds property', function () {
    expect(AclRuleFilter::enforcingDeviceIds()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('enforcingDeviceFilter.deviceIds.contains(550e8400-e29b-41d4-a716-446655440000)');
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(AclRuleFilter::metadataOrigin()->equals('USER_DEFINED')->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

test('source filter properties map to sourceFilter.*', function () {
    expect(AclRuleFilter::sourceFilterType()->equals('NETWORKS')->toString())
        ->toBe("sourceFilter.type.eq('NETWORKS')");
    expect(AclRuleFilter::sourceIpAddressesOrSubnets()->contains('10.0.0.0/8')->toString())
        ->toBe("sourceFilter.ipAddressesOrSubnets.contains('10.0.0.0/8')");
    expect(AclRuleFilter::sourcePortFilter()->contains(22)->toString())
        ->toBe('sourceFilter.portFilter.contains(22)');
    expect(AclRuleFilter::sourceNetworkIds()->containsAny(['550e8400-e29b-41d4-a716-446655440000'])->toString())
        ->toBe('sourceFilter.networkIds.containsAny(550e8400-e29b-41d4-a716-446655440000)');
    expect(AclRuleFilter::sourceMacAddresses()->contains('94:2a:6f:26:c6:ca')->toString())
        ->toBe("sourceFilter.macAddresses.contains('94:2a:6f:26:c6:ca')");
    expect(AclRuleFilter::sourcePrefixLength()->greaterThanOrEqual(24)->toString())
        ->toBe('sourceFilter.prefixLength.ge(24)');
});

test('destination filter properties map to destinationFilter.*', function () {
    expect(AclRuleFilter::destinationFilterType()->isNotNull()->toString())
        ->toBe('destinationFilter.type.isNotNull()');
    expect(AclRuleFilter::destinationIpAddressesOrSubnets()->contains('192.168.1.0/24')->toString())
        ->toBe("destinationFilter.ipAddressesOrSubnets.contains('192.168.1.0/24')");
    expect(AclRuleFilter::destinationPortFilter()->contains(443)->toString())
        ->toBe('destinationFilter.portFilter.contains(443)');
    expect(AclRuleFilter::destinationNetworkIds()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('destinationFilter.networkIds.contains(550e8400-e29b-41d4-a716-446655440000)');
    expect(AclRuleFilter::destinationMacAddresses()->contains('94:2a:6f:26:c6:ca')->toString())
        ->toBe("destinationFilter.macAddresses.contains('94:2a:6f:26:c6:ca')");
    expect(AclRuleFilter::destinationPrefixLength()->equals(48)->toString())
        ->toBe('destinationFilter.prefixLength.eq(48)');
});
