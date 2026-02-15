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
