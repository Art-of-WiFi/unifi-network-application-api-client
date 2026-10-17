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
 *
 * @example
 * ```php
 * // Find traffic matching lists by name
 * TrafficMatchingListFilter::name()->like('Blocked*')
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
     * @deprecated `type` is not a filterable property in the UniFi API; the API rejects it with
     *             `400 api.request.invalid-filter`. Will be removed in 2.0. Fetch the lists and
     *             inspect their `type` field instead.
     */
    public static function type(): static
    {
        return static::where('type');
    }
}
