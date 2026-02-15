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

test('networkIds() sets networkIds property', function () {
    expect(FirewallZoneFilter::networkIds()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe("networkIds.contains('550e8400-e29b-41d4-a716-446655440000')");
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
