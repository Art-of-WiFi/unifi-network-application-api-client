<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\TrafficMatchingLists;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Traffic Matching List Filter Builder
 *
 * Provides a type-safe, fluent interface for building traffic matching list filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - type (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find traffic matching lists by name
 * TrafficMatchingListFilter::name()->like('Blocked*')
 *
 * // Find by type
 * TrafficMatchingListFilter::type()->equals('IP_ADDRESS')
 * ```
 */
class TrafficMatchingListFilter extends Filter
{
    /**
     * Filter by traffic matching list ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by traffic matching list name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by traffic matching list type
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function type(): static
    {
        return static::where('type');
    }
}
