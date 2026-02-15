<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Devices;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Device Filter Builder
 *
 * Provides a type-safe, fluent interface for building device filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - macAddress (STRING) - eq, ne, in, notIn
 * - ipAddress (STRING) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - model (STRING) - eq, ne, in, notIn
 * - state (STRING) - eq, ne, in, notIn
 * - supported (BOOLEAN) - eq, ne
 * - firmwareVersion (STRING) - isNull, isNotNull, eq, ne, gt, ge, lt, le, like, in, notIn
 * - firmwareUpdatable (BOOLEAN) - eq, ne
 * - features (SET) - isEmpty, contains, containsAny, containsAll, containsExactly
 * - interfaces (SET) - isEmpty, contains, containsAny, containsAll, containsExactly
 *
 * @example
 * ```php
 * // Find all access points
 * DeviceFilter::name()->like('AP-*')
 *
 * // Find devices by model
 * DeviceFilter::model()->in(['U6-LR', 'U6-PRO'])
 *
 * // Find devices needing updates
 * DeviceFilter::and(
 *     DeviceFilter::firmwareUpdatable()->equals(true),
 *     DeviceFilter::supported()->equals(true)
 * )
 *
 * // Find devices with WiFi 6
 * DeviceFilter::features()->contains('wifi6')
 * ```
 */
class DeviceFilter extends Filter
{
    /**
     * Filter by device ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by device MAC address
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function macAddress(): static
    {
        return static::where('macAddress');
    }

    /**
     * Filter by device IP address
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function ipAddress(): static
    {
        return static::where('ipAddress');
    }

    /**
     * Filter by device name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by device model
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function model(): static
    {
        return static::where('model');
    }

    /**
     * Filter by device state
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function state(): static
    {
        return static::where('state');
    }

    /**
     * Filter by supported status
     *
     * Supported operators: eq, ne
     */
    public static function supported(): static
    {
        return static::where('supported');
    }

    /**
     * Filter by firmware version
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le, like, in, notIn
     */
    public static function firmwareVersion(): static
    {
        return static::where('firmwareVersion');
    }

    /**
     * Filter by firmware updatable status
     *
     * Supported operators: eq, ne
     */
    public static function firmwareUpdatable(): static
    {
        return static::where('firmwareUpdatable');
    }

    /**
     * Filter by device features (set operations)
     *
     * Supported operators: isEmpty, contains, containsAny, containsAll, containsExactly
     */
    public static function features(): static
    {
        return static::where('features');
    }

    /**
     * Filter by device interfaces (set operations)
     *
     * Supported operators: isEmpty, contains, containsAny, containsAll, containsExactly
     */
    public static function interfaces(): static
    {
        return static::where('interfaces');
    }

    // ===== Preset Filters =====

    /**
     * Filter for devices needing firmware updates
     */
    public static function needsFirmwareUpdate(): static
    {
        return static::firmwareUpdatable()->equals(true);
    }

    /**
     * Filter for supported devices only
     */
    public static function supportedOnly(): static
    {
        return static::supported()->equals(true);
    }

    /**
     * Filter for devices with WiFi 6 support
     */
    public static function wifi6Capable(): static
    {
        return static::features()->contains('wifi6');
    }

    /**
     * Filter for PoE-capable devices
     */
    public static function poeCapable(): static
    {
        return static::features()->contains('poe');
    }
}
