<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Networks\NetworkFilter;

test('management() sets management property', function () {
    expect(NetworkFilter::management()->equals('LAN')->toString())
        ->toBe("management.eq('LAN')");
});

test('id() sets id property', function () {
    expect(NetworkFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(NetworkFilter::name()->like('Guest*')->toString())
        ->toBe("name.like('Guest*')");
});

test('enabled() sets enabled property', function () {
    expect(NetworkFilter::enabled()->equals(true)->toString())
        ->toBe('enabled.eq(true)');
});

test('vlanId() sets vlanId property', function () {
    expect(NetworkFilter::vlanId()->equals(100)->toString())
        ->toBe('vlanId.eq(100)');
});

test('deviceId() sets deviceId property', function () {
    expect(NetworkFilter::deviceId()->isNull()->toString())
        ->toBe('deviceId.isNull()');
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(NetworkFilter::metadataOrigin()->equals('USER')->toString())
        ->toBe("metadata.origin.eq('USER')");
});

// Preset filters

test('enabledOnly() preset', function () {
    expect(NetworkFilter::enabledOnly()->toString())
        ->toBe('enabled.eq(true)');
});

test('disabledOnly() preset', function () {
    expect(NetworkFilter::disabledOnly()->toString())
        ->toBe('enabled.eq(false)');
});

test('guestNetworks() preset', function () {
    expect(NetworkFilter::guestNetworks()->toString())
        ->toBe("name.like('*Guest*')");
});

test('iotNetworks() preset', function () {
    expect(NetworkFilter::iotNetworks()->toString())
        ->toBe("name.like('*IoT*')");
});

test('unassignedNetworks() preset', function () {
    expect(NetworkFilter::unassignedNetworks()->toString())
        ->toBe('deviceId.isNull()');
});
