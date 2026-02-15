<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Device Tags Filter Builder
 *
 * Provides a type-safe, fluent interface for building device tag filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - deviceIds (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 *
 * @example
 * ```php
 * // Find tag by name
 * DeviceTagsFilter::name()->equals('Office')
 *
 * // Find tags containing specific device
 * DeviceTagsFilter::deviceIds()->contains('device-uuid-here')
 *
 * // Find tags containing any of these devices
 * DeviceTagsFilter::deviceIds()->containsAny(['uuid1', 'uuid2'])
 * ```
 */
class DeviceTagsFilter extends Filter
{
    /**
     * Filter by tag ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by tag name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by device IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function deviceIds(): static
    {
        return static::where('deviceIds');
    }
}
