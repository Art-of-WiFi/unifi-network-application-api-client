<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching\LagFilter;

test('id() sets id property', function () {
    expect(LagFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('type() sets type property', function () {
    expect(LagFilter::type()->equals('SWITCH_STACK')->toString())
        ->toBe("type.eq('SWITCH_STACK')");
});

test('switchStackId() sets switchStackId property', function () {
    expect(LagFilter::switchStackId()->equals('stack-uuid')->toString())
        ->toBe("switchStackId.eq('stack-uuid')");
});

test('switchStackId() isNull', function () {
    expect(LagFilter::switchStackId()->isNull()->toString())
        ->toBe('switchStackId.isNull()');
});

test('switchStackId() isNotNull', function () {
    expect(LagFilter::switchStackId()->isNotNull()->toString())
        ->toBe('switchStackId.isNotNull()');
});

test('mcLagDomainId() sets mcLagDomainId property', function () {
    expect(LagFilter::mcLagDomainId()->equals('domain-uuid')->toString())
        ->toBe("mcLagDomainId.eq('domain-uuid')");
});

test('mcLagDomainId() isNotNull', function () {
    expect(LagFilter::mcLagDomainId()->isNotNull()->toString())
        ->toBe('mcLagDomainId.isNotNull()');
});

test('membersDeviceId() sets members.deviceId property', function () {
    expect(LagFilter::membersDeviceId()->contains('device-uuid')->toString())
        ->toBe("members.deviceId.contains('device-uuid')");
});

test('membersPortIdxs() sets members.portIdxs property', function () {
    expect(LagFilter::membersPortIdxs()->contains(1)->toString())
        ->toBe('members.portIdxs.contains(1)');
});

test('membersPortIdxs() containsAny with integers', function () {
    expect(LagFilter::membersPortIdxs()->containsAny([1, 2, 3])->toString())
        ->toBe('members.portIdxs.containsAny(1, 2, 3)');
});

test('metadataOrigin() sets metadata.origin property', function () {
    expect(LagFilter::metadataOrigin()->equals('USER_DEFINED')->toString())
        ->toBe("metadata.origin.eq('USER_DEFINED')");
});

// Preset filters

test('localLags() preset', function () {
    expect(LagFilter::localLags()->toString())
        ->toBe("type.eq('LOCAL')");
});

test('switchStackLags() preset', function () {
    expect(LagFilter::switchStackLags()->toString())
        ->toBe("type.eq('SWITCH_STACK')");
});

test('mcLags() preset', function () {
    expect(LagFilter::mcLags()->toString())
        ->toBe("type.eq('MULTI_CHASSIS')");
});
