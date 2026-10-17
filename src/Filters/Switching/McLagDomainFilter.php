<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * MC-LAG Domain Filter Builder
 *
 * Provides a type-safe, fluent interface for building MC-LAG domain filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - peers.deviceId (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 * - metadata.origin (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find MC-LAG domain by name
 * McLagDomainFilter::name()->like('MLAG-*')
 *
 * // Find domains containing a specific peer device
 * McLagDomainFilter::peersDeviceId()->contains('550e8400-e29b-41d4-a716-446655440000')
 * ```
 */
class McLagDomainFilter extends Filter
{
    /**
     * Filter by MC-LAG domain ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by MC-LAG domain name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by peer device IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function peersDeviceId(): static
    {
        return static::where('peers.deviceId');
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
