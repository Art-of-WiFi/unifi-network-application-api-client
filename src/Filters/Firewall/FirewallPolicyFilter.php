<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Firewall;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Firewall Policy Filter Builder
 *
 * Provides a type-safe, fluent interface for building firewall policy filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - source.zoneId (UUID) - eq, ne, in, notIn
 * - destination.zoneId (UUID) - eq, ne, in, notIn
 * - metadata.origin (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find policies by name
 * FirewallPolicyFilter::name()->like('Block*')
 *
 * // Find policies for a specific source zone
 * FirewallPolicyFilter::sourceZoneId()->equals('zone-uuid-here')
 *
 * // Combine filters
 * FirewallPolicyFilter::and(
 *     FirewallPolicyFilter::sourceZoneId()->equals('zone-1'),
 *     FirewallPolicyFilter::destinationZoneId()->equals('zone-2')
 * )
 * ```
 */
class FirewallPolicyFilter extends Filter
{
    /**
     * Filter by policy ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by policy name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by source zone ID
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function sourceZoneId(): static
    {
        return static::where('source.zoneId');
    }

    /**
     * Filter by destination zone ID
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function destinationZoneId(): static
    {
        return static::where('destination.zoneId');
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
}
