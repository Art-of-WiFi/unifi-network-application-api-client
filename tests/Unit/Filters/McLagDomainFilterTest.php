<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\McLagDomainFilter;

test('id() sets id property', function () {
    expect(McLagDomainFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(McLagDomainFilter::name()->like('MLAG-*')->toString())
        ->toBe("name.like('MLAG-*')");
});

test('peersDeviceId() sets peers.deviceId property', function () {
    expect(McLagDomainFilter::peersDeviceId()->contains('550e8400-e29b-41d4-a716-446655440000')->toString())
        ->toBe("peers.deviceId.contains('550e8400-e29b-41d4-a716-446655440000')");
});

test('peersDeviceId() containsAll', function () {
    expect(McLagDomainFilter::peersDeviceId()->containsAll(['device-1', 'device-2'])->toString())
        ->toBe("peers.deviceId.containsAll('device-1', 'device-2')");
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(McLagDomainFilter::metadataOrigin()->equals('USER_DEFINED')->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

test('and() combines MC-LAG domain filters', function () {
    $filter = McLagDomainFilter::and(
        McLagDomainFilter::name()->like('MLAG-*'),
        McLagDomainFilter::peersDeviceId()->contains('device-1')
    );

    expect($filter->toString())
        ->toBe("and(name.like('MLAG-*'), peers.deviceId.contains('device-1'))");
});
