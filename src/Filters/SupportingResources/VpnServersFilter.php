<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * VPN Servers Filter Builder
 *
 * Provides a type-safe, fluent interface for building VPN server filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - type (STRING) - eq, ne, in, notIn
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - enabled (BOOLEAN) - eq, ne
 * - metadata.origin (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find enabled VPN servers
 * VpnServersFilter::enabled()->equals(true)
 *
 * // Find server by name
 * VpnServersFilter::name()->like('Remote*')
 *
 * // Find servers by type
 * VpnServersFilter::type()->equals('L2TP')
 * ```
 */
class VpnServersFilter extends Filter
{
    /**
     * Filter by VPN server type
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function type(): static
    {
        return static::where('type');
    }

    /**
     * Filter by server ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by server name
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
     * Filter for enabled VPN servers only
     */
    public static function enabledOnly(): static
    {
        return static::enabled()->equals(true);
    }

    /**
     * Filter for disabled VPN servers only
     */
    public static function disabledOnly(): static
    {
        return static::enabled()->equals(false);
    }
}
