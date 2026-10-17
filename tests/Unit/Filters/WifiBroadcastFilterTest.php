<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\WifiBroadcasts\WifiBroadcastFilter;

test('id() sets id property', function () {
    expect(WifiBroadcastFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(WifiBroadcastFilter::name()->like('Guest*')->toString())
        ->toBe("name.like('Guest*')");
});

test('type() sets type property', function () {
    expect(WifiBroadcastFilter::type()->equals('STANDARD')->toString())
        ->toBe("type.eq('STANDARD')");
});

test('enabled() sets enabled property', function () {
    expect(WifiBroadcastFilter::enabled()->equals(true)->toString())
        ->toBe('enabled.eq(true)');
});

// Preset filters

test('enabledOnly() preset', function () {
    expect(WifiBroadcastFilter::enabledOnly()->toString())
        ->toBe('enabled.eq(true)');
});

test('disabledOnly() preset', function () {
    expect(WifiBroadcastFilter::disabledOnly()->toString())
        ->toBe('enabled.eq(false)');
});

test('broadcastingFrequenciesGHz() accepts decimals', function () {
    expect(WifiBroadcastFilter::broadcastingFrequenciesGHz()->contains(2.4)->toString())
        ->toBe('broadcastingFrequenciesGHz.contains(2.4)');
});

test('broadcastingFrequenciesGHz() containsAll with mixed numbers', function () {
    expect(WifiBroadcastFilter::broadcastingFrequenciesGHz()->containsAll([2.4, 5])->toString())
        ->toBe('broadcastingFrequenciesGHz.containsAll(2.4, 5)');
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(WifiBroadcastFilter::metadataOrigin()->equals('USER_DEFINED')->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

test('networkType() and networkId() map to network.*', function () {
    expect(WifiBroadcastFilter::networkType()->equals('SPECIFIC')->toString())
        ->toBe("network.type.eq('SPECIFIC')");
    expect(WifiBroadcastFilter::networkId()->equals('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('network.networkId.eq(550e8400-e29b-41d4-a716-446655440000)');
});

test('securityType() sets securityConfiguration.type property', function () {
    expect(WifiBroadcastFilter::securityType()->equals('WPA2_PERSONAL')->toString())
        ->toBe("securityConfiguration.type.eq('WPA2_PERSONAL')");
});

test('broadcasting device filter properties map to broadcastingDeviceFilter.*', function () {
    expect(WifiBroadcastFilter::broadcastingDeviceFilterType()->isNull()->toString())
        ->toBe('broadcastingDeviceFilter.type.isNull()');
    expect(WifiBroadcastFilter::broadcastingDeviceIds()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('broadcastingDeviceFilter.deviceIds.contains(550e8400-e29b-41d4-a716-446655440000)');
    expect(WifiBroadcastFilter::broadcastingDeviceTagIds()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('broadcastingDeviceFilter.deviceTagIds.contains(550e8400-e29b-41d4-a716-446655440000)');
});

test('hotspotType() sets hotspotConfiguration.type property', function () {
    expect(WifiBroadcastFilter::hotspotType()->equals('CAPTIVE_PORTAL')->toString())
        ->toBe("hotspotConfiguration.type.eq('CAPTIVE_PORTAL')");
});
