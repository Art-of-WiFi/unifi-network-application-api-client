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
