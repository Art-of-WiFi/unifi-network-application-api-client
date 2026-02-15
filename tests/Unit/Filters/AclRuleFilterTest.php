<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\AclRules\AclRuleFilter;

test('id() sets id property', function () {
    expect(AclRuleFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('name() sets name property', function () {
    expect(AclRuleFilter::name()->like('Block*')->toString())
        ->toBe("name.like('Block*')");
});

test('type() sets type property', function () {
    expect(AclRuleFilter::type()->equals('LAN')->toString())
        ->toBe("type.eq('LAN')");
});

test('enabled() sets enabled property', function () {
    expect(AclRuleFilter::enabled()->equals(true)->toString())
        ->toBe('enabled.eq(true)');
});

test('action() sets action property', function () {
    expect(AclRuleFilter::action()->equals('BLOCK')->toString())
        ->toBe("action.eq('BLOCK')");
});

// Preset filters

test('enabledOnly() preset', function () {
    expect(AclRuleFilter::enabledOnly()->toString())
        ->toBe('enabled.eq(true)');
});

test('disabledOnly() preset', function () {
    expect(AclRuleFilter::disabledOnly()->toString())
        ->toBe('enabled.eq(false)');
});
