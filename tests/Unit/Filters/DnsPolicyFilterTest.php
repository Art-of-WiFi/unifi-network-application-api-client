<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\DnsPolicies\DnsPolicyFilter;

test('type() sets type property', function () {
    expect(DnsPolicyFilter::type()->equals('A_RECORD')->toString())
        ->toBe("type.eq('A_RECORD')");
});

test('domain() supports like', function () {
    expect(DnsPolicyFilter::domain()->like('*.example.com')->toString())
        ->toBe("domain.like('*.example.com')");
});

test('ipv4Address() supports like', function () {
    expect(DnsPolicyFilter::ipv4Address()->like('192.168.1.*')->toString())
        ->toBe("ipv4Address.like('192.168.1.*')");
});

test('ttlSeconds() supports in', function () {
    expect(DnsPolicyFilter::ttlSeconds()->in([300, 3600])->toString())
        ->toBe('ttlSeconds.in(300, 3600)');
});

// Preset filters use the type values from the API specification

test('record type presets use the API type values', function (string $preset, string $type) {
    expect(DnsPolicyFilter::$preset()->toString())
        ->toBe("type.eq('{$type}')");
})->with([
    ['aRecords', 'A_RECORD'],
    ['aaaaRecords', 'AAAA_RECORD'],
    ['cnameRecords', 'CNAME_RECORD'],
    ['mxRecords', 'MX_RECORD'],
    ['txtRecords', 'TXT_RECORD'],
    ['srvRecords', 'SRV_RECORD'],
    ['forwardDomains', 'FORWARD_DOMAIN'],
]);

test('enabledOnly() preset', function () {
    expect(DnsPolicyFilter::enabledOnly()->toString())
        ->toBe('enabled.eq(true)');
});
