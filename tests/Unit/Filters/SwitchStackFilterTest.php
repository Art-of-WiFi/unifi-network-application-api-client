<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\SwitchStackFilter;

test('id() sets id property', function () {
    expect(SwitchStackFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(SwitchStackFilter::name()->like('Stack-*')->toString())
        ->toBe("name.like('Stack-*')");
});

test('unitsId() sets units.id property with integer values', function () {
    expect(SwitchStackFilter::unitsId()->contains(1)->toString())
        ->toBe('units.id.contains(1)');
});

test('unitsId() containsAll', function () {
    expect(SwitchStackFilter::unitsId()->containsAll([1, 2])->toString())
        ->toBe('units.id.containsAll(1, 2)');
});

test('unitsMacAddress() sets units.macAddress property', function () {
    expect(SwitchStackFilter::unitsMacAddress()->contains('94:2a:6f:26:c6:ca')->toString())
        ->toBe("units.macAddress.contains('94:2a:6f:26:c6:ca')");
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(SwitchStackFilter::metadataOrigin()->equals('USER_DEFINED')->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

test('and() combines switch stack filters', function () {
    $filter = SwitchStackFilter::and(
        SwitchStackFilter::name()->like('Stack-*'),
        SwitchStackFilter::unitsMacAddress()->contains('94:2a:6f:26:c6:ca')
    );

    expect($filter->toString())
        ->toBe("and(name.like('Stack-*'), units.macAddress.contains('94:2a:6f:26:c6:ca'))");
});
