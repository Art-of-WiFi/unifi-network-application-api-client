<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Firewall\FirewallZoneFilter;

test('id() sets id property', function () {
    expect(FirewallZoneFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(FirewallZoneFilter::name()->like('DMZ*')->toString())
        ->toBe("name.like('DMZ*')");
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(FirewallZoneFilter::metadataOrigin()->equals('USER_DEFINED')->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

test('metadataConfigurable() sets metadata.configurable property', function () {
    expect(FirewallZoneFilter::metadataConfigurable()->equals(true)->toString())
        ->toBe('metadata.configurable.eq(true)');
});

test('customZones() preset', function () {
    expect(FirewallZoneFilter::customZones()->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

// Deprecated: networkIds is not filterable in the UniFi API, kept for backward compatibility

test('networkIds() sets networkIds property', function () {
    expect(FirewallZoneFilter::networkIds()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe('networkIds.contains(550e8400-e29b-41d4-a716-446655440000)');
});

test('networkIds() isEmpty', function () {
    expect(FirewallZoneFilter::networkIds()->isEmpty()->toString())
        ->toBe('networkIds.isEmpty()');
});

// Preset filters

test('emptyZones() preset', function () {
    expect(FirewallZoneFilter::emptyZones()->toString())
        ->toBe('networkIds.isEmpty()');
});
