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
 * - networkIds (SET(UUID)) - contains, containsAny, containsAll, isEmpty
 *
 * @example
 * ```php
 * // Find firewall zone by name
 * FirewallZoneFilter::name()->like('DMZ*')
 *
 * // Find zones containing a specific network
 * FirewallZoneFilter::networkIds()->contains('550e8400-e29b-41d4-a716-446655440000')
 *
 * // Find zones without assigned networks
 * FirewallZoneFilter::networkIds()->isEmpty()
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
     * Filter by network IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, isEmpty
     */
    public static function networkIds(): static
    {
        return static::where('networkIds');
    }

    // ===== Preset Filters =====

    /**
     * Filter for zones without assigned networks
     */
    public static function emptyZones(): static
    {
        return static::networkIds()->isEmpty();
    }
}
