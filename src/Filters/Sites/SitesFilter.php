<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Sites;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Sites Filter Builder
 *
 * Provides a type-safe, fluent interface for building site filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - internalReference (STRING) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find site by ID
 * SitesFilter::id()->equals('550e8400-e29b-41d4-a716-446655440000')
 *
 * // Find site by name
 * SitesFilter::name()->equals('Default')
 *
 * // Find multiple sites by ID
 * SitesFilter::id()->in(['uuid1', 'uuid2', 'uuid3'])
 * ```
 */
class SitesFilter extends Filter
{
    /**
     * Filter by site ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by internal reference
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function internalReference(): static
    {
        return static::where('internalReference');
    }

    /**
     * Filter by site name
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function name(): static
    {
        return static::where('name');
    }
}
