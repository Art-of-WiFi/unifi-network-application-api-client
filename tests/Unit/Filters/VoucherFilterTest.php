<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Hotspot\VoucherFilter;

test('id() sets id property', function () {
    expect(VoucherFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(VoucherFilter::name()->like('Event*')->toString())
        ->toBe("name.like('Event*')");
});

test('code() sets code property', function () {
    expect(VoucherFilter::code()->equals('ABC123')->toString())
        ->toBe("code.eq('ABC123')");
});

test('expired() sets expired property', function () {
    expect(VoucherFilter::expired()->equals(true)->toString())
        ->toBe('expired.eq(true)');
});

// Preset filters

test('active() preset', function () {
    expect(VoucherFilter::active()->toString())
        ->toBe('expired.eq(false)');
});

test('expiredOnly() preset', function () {
    expect(VoucherFilter::expiredOnly()->toString())
        ->toBe('expired.eq(true)');
});

test('unused() preset', function () {
    expect(VoucherFilter::unused()->toString())
        ->toBe('authorizedGuestCount.eq(0)');
});

test('createdAt() accepts a DateTimeInterface', function () {
    $date = new DateTimeImmutable('2025-01-01 00:00:00', new DateTimeZone('UTC'));

    expect(VoucherFilter::createdAt()->greaterThan($date)->toString())
        ->toBe('createdAt.gt(2025-01-01T00:00:00Z)');
});

test('timestamp properties map to the correct names', function () {
    $date = new DateTimeImmutable('2025-06-30 12:00:00', new DateTimeZone('UTC'));

    expect(VoucherFilter::activatedAt()->lessThan($date)->toString())
        ->toBe('activatedAt.lt(2025-06-30T12:00:00Z)');
    expect(VoucherFilter::expiresAt()->lessThanOrEqual($date)->toString())
        ->toBe('expiresAt.le(2025-06-30T12:00:00Z)');
});

test('numeric properties map to the correct names', function () {
    expect(VoucherFilter::authorizedGuestLimit()->isNull()->toString())
        ->toBe('authorizedGuestLimit.isNull()');
    expect(VoucherFilter::authorizedGuestCount()->greaterThan(0)->toString())
        ->toBe('authorizedGuestCount.gt(0)');
    expect(VoucherFilter::timeLimitMinutes()->equals(1440)->toString())
        ->toBe('timeLimitMinutes.eq(1440)');
    expect(VoucherFilter::dataUsageLimitMBytes()->isNotNull()->toString())
        ->toBe('dataUsageLimitMBytes.isNotNull()');
    expect(VoucherFilter::rxRateLimitKbps()->greaterThanOrEqual(1000)->toString())
        ->toBe('rxRateLimitKbps.ge(1000)');
    expect(VoucherFilter::txRateLimitKbps()->lessThan(500)->toString())
        ->toBe('txRateLimitKbps.lt(500)');
});
