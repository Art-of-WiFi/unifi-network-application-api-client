<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Firewall;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Firewall Zone Filter Builder
 *
 * Provides a type-safe, fluent interface for building firewall zone filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - metadata.origin (STRING) - eq, ne, in, notIn
 * - metadata.configurable (BOOLEAN) - eq, ne, isNull, isNotNull
 *
 * @example
 * ```php
 * // Find firewall zone by name
 * FirewallZoneFilter::name()->like('DMZ*')
 *
 * // Find user-defined (custom) zones
 * FirewallZoneFilter::customZones()
 *
 * // Find zones whose attached networks can be configured
 * FirewallZoneFilter::metadataConfigurable()->equals(true)
 * ```
 */
class FirewallZoneFilter extends Filter
{
    /**
     * Filter by firewall zone ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by firewall zone name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by metadata origin (USER_DEFINED or SYSTEM_DEFINED)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function metadataOrigin(): static
    {
        return static::where('metadata.origin');
    }

    /**
     * Filter by whether the zone is configurable
     *
     * Supported operators: eq, ne, isNull, isNotNull
     */
    public static function metadataConfigurable(): static
    {
        return static::where('metadata.configurable');
    }

    /**
     * Filter by network IDs (set operations)
     *
     * @deprecated `networkIds` is not a filterable property in the UniFi API; the API rejects
     *             it with `400 api.request.invalid-filter`. Will be removed in 2.0. Fetch the
     *             zones and inspect their `networkIds` field instead.
     */
    public static function networkIds(): static
    {
        return static::where('networkIds');
    }

    // ===== Preset Filters =====

    /**
     * Filter for user-defined (custom) zones only
     */
    public static function customZones(): static
    {
        return static::metadataOrigin()->equals('USER_DEFINED');
    }

    /**
     * Filter for zones without assigned networks
     *
     * @deprecated Relies on the `networkIds` property, which the UniFi API does not accept as a
     *             filter. Will be removed in 2.0.
     */
    public static function emptyZones(): static
    {
        return static::networkIds()->isEmpty();
    }
}
