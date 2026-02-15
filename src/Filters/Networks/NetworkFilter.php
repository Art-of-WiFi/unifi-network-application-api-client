<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Networks;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Network Filter Builder
 *
 * Provides a type-safe, fluent interface for building network filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - management (STRING) - eq, ne, in, notIn
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - enabled (BOOLEAN) - eq, ne
 * - vlanId (INTEGER) - eq, ne, gt, ge, lt, le, in, notIn
 * - deviceId (UUID) - eq, ne, in, notIn, isNull, isNotNull
 * - metadata.origin (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find network by name
 * NetworkFilter::name()->equals('LAN')
 *
 * // Find enabled networks
 * NetworkFilter::enabled()->equals(true)
 *
 * // Find networks by VLAN ID range
 * NetworkFilter::and(
 *     NetworkFilter::vlanId()->greaterThan(10),
 *     NetworkFilter::vlanId()->lessThan(100)
 * )
 *
 * // Find guest networks
 * NetworkFilter::name()->like('Guest*')
 * ```
 */
class NetworkFilter extends Filter
{
    /**
     * Filter by network management type
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function management(): static
    {
        return static::where('management');
    }

    /**
     * Filter by network ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by network name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by enabled status
     *
     * Supported operators: eq, ne
     */
    public static function enabled(): static
    {
        return static::where('enabled');
    }

    /**
     * Filter by VLAN ID
     *
     * Supported operators: eq, ne, gt, ge, lt, le, in, notIn
     */
    public static function vlanId(): static
    {
        return static::where('vlanId');
    }

    /**
     * Filter by device ID
     *
     * Supported operators: eq, ne, in, notIn, isNull, isNotNull
     */
    public static function deviceId(): static
    {
        return static::where('deviceId');
    }

    /**
     * Filter by metadata origin
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function metadataOrigin(): static
    {
        return static::where('metadata.origin');
    }

    // ===== Preset Filters =====

    /**
     * Filter for enabled networks only
     */
    public static function enabledOnly(): static
    {
        return static::enabled()->equals(true);
    }

    /**
     * Filter for disabled networks only
     */
    public static function disabledOnly(): static
    {
        return static::enabled()->equals(false);
    }

    /**
     * Filter for guest networks (by name pattern)
     */
    public static function guestNetworks(): static
    {
        return static::name()->like('*Guest*');
    }

    /**
     * Filter for IoT networks (by name pattern)
     */
    public static function iotNetworks(): static
    {
        return static::name()->like('*IoT*');
    }

    /**
     * Filter for networks without assigned devices
     */
    public static function unassignedNetworks(): static
    {
        return static::deviceId()->isNull();
    }
}
