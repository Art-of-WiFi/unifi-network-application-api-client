<?php

declare(strict_types=1);

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Devices\DeviceFilter;

test('id() sets id property', function () {
    expect(DeviceFilter::id()->equals('abc-123')->toString())
        ->toBe("id.eq('abc-123')");
});

test('macAddress() sets macAddress property', function () {
    expect(DeviceFilter::macAddress()->equals('AA:BB:CC:DD:EE:FF')->toString())
        ->toBe("macAddress.eq('AA:BB:CC:DD:EE:FF')");
});

test('ipAddress() sets ipAddress property', function () {
    expect(DeviceFilter::ipAddress()->equals('192.168.1.1')->toString())
        ->toBe("ipAddress.eq('192.168.1.1')");
});

test('name() sets name property', function () {
    expect(DeviceFilter::name()->like('AP-*')->toString())
        ->toBe("name.like('AP-*')");
});

test('model() sets model property', function () {
    expect(DeviceFilter::model()->equals('U6-LR')->toString())
        ->toBe("model.eq('U6-LR')");
});

test('state() sets state property', function () {
    expect(DeviceFilter::state()->equals('CONNECTED')->toString())
        ->toBe("state.eq('CONNECTED')");
});

test('supported() sets supported property', function () {
    expect(DeviceFilter::supported()->equals(true)->toString())
        ->toBe('supported.eq(true)');
});

test('firmwareVersion() sets firmwareVersion property', function () {
    expect(DeviceFilter::firmwareVersion()->greaterThan('6.0.0')->toString())
        ->toBe("firmwareVersion.gt('6.0.0')");
});

test('firmwareUpdatable() sets firmwareUpdatable property', function () {
    expect(DeviceFilter::firmwareUpdatable()->equals(true)->toString())
        ->toBe('firmwareUpdatable.eq(true)');
});

test('features() sets features property', function () {
    expect(DeviceFilter::features()->contains('wifi6')->toString())
        ->toBe("features.contains('wifi6')");
});

test('interfaces() sets interfaces property', function () {
    expect(DeviceFilter::interfaces()->isEmpty()->toString())
        ->toBe('interfaces.isEmpty()');
});

// Preset filters

test('needsFirmwareUpdate() preset', function () {
    expect(DeviceFilter::needsFirmwareUpdate()->toString())
        ->toBe('firmwareUpdatable.eq(true)');
});

test('supportedOnly() preset', function () {
    expect(DeviceFilter::supportedOnly()->toString())
        ->toBe('supported.eq(true)');
});

test('wifi6Capable() preset', function () {
    expect(DeviceFilter::wifi6Capable()->toString())
        ->toBe("features.contains('wifi6')");
});

test('poeCapable() preset', function () {
    expect(DeviceFilter::poeCapable()->toString())
        ->toBe("features.contains('poe')");
});
