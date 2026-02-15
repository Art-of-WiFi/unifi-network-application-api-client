<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientAccessType;
use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientType;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Clients\ClientFilter;

test('id() sets id property', function () {
    expect(ClientFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('type() sets type property', function () {
    expect(ClientFilter::type()->equals(ClientType::WIRELESS)->toString())
        ->toBe("type.eq('WIRELESS')");
});

test('macAddress() sets macAddress property', function () {
    expect(ClientFilter::macAddress()->isNotNull()->toString())
        ->toBe('macAddress.isNotNull()');
});

test('ipAddress() sets ipAddress property', function () {
    expect(ClientFilter::ipAddress()->equals('10.0.0.1')->toString())
        ->toBe("ipAddress.eq('10.0.0.1')");
});

test('connectedAt() sets connectedAt property', function () {
    expect(ClientFilter::connectedAt()->greaterThan('2024-01-01')->toString())
        ->toBe("connectedAt.gt('2024-01-01')");
});

test('accessType() sets access.type property', function () {
    expect(ClientFilter::accessType()->equals(ClientAccessType::GUEST)->toString())
        ->toBe("access.type.eq('GUEST')");
});

test('accessAuthorized() sets access.authorized property', function () {
    expect(ClientFilter::accessAuthorized()->equals(true)->toString())
        ->toBe('access.authorized.eq(true)');
});

// Preset filters

test('wireless() preset', function () {
    expect(ClientFilter::wireless()->toString())
        ->toBe("type.eq('WIRELESS')");
});

test('wired() preset', function () {
    expect(ClientFilter::wired()->toString())
        ->toBe("type.eq('WIRED')");
});

test('vpn() preset', function () {
    expect(ClientFilter::vpn()->toString())
        ->toBe("type.eq('VPN')");
});

test('teleport() preset', function () {
    expect(ClientFilter::teleport()->toString())
        ->toBe("type.eq('TELEPORT')");
});

test('guests() preset', function () {
    expect(ClientFilter::guests()->toString())
        ->toBe("access.type.eq('GUEST')");
});

test('defaultAccess() preset', function () {
    expect(ClientFilter::defaultAccess()->toString())
        ->toBe("access.type.eq('DEFAULT')");
});

test('wirelessGuests() preset', function () {
    expect(ClientFilter::wirelessGuests()->toString())
        ->toBe("and(type.eq('WIRELESS'), access.type.eq('GUEST'))");
});

test('wiredGuests() preset', function () {
    expect(ClientFilter::wiredGuests()->toString())
        ->toBe("and(type.eq('WIRED'), access.type.eq('GUEST'))");
});

test('authorizedGuests() preset', function () {
    expect(ClientFilter::authorizedGuests()->toString())
        ->toBe("and(access.type.eq('GUEST'), access.authorized.eq(true))");
});

test('unauthorizedGuests() preset', function () {
    expect(ClientFilter::unauthorizedGuests()->toString())
        ->toBe("and(access.type.eq('GUEST'), access.authorized.eq(false))");
});

test('withIpAddress() preset', function () {
    expect(ClientFilter::withIpAddress()->toString())
        ->toBe('ipAddress.isNotNull()');
});

test('withoutIpAddress() preset', function () {
    expect(ClientFilter::withoutIpAddress()->toString())
        ->toBe('ipAddress.isNull()');
});
