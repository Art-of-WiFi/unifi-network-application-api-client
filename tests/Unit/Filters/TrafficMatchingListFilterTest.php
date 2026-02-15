<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\TrafficMatchingLists\TrafficMatchingListFilter;

test('id() sets id property', function () {
    expect(TrafficMatchingListFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(TrafficMatchingListFilter::name()->like('Blocked*')->toString())
        ->toBe("name.like('Blocked*')");
});

test('type() sets type property', function () {
    expect(TrafficMatchingListFilter::type()->equals('IP_ADDRESS')->toString())
        ->toBe("type.eq('IP_ADDRESS')");
});
