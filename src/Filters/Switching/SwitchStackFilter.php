<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Switch Stack Filter Builder
 *
 * Provides a type-safe, fluent interface for building switch stack filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - units.id (SET(INTEGER)) - contains, containsAny, containsAll, containsExactly
 * - units.macAddress (SET(STRING)) - contains, containsAny, containsAll, containsExactly
 * - metadata.origin (STRING) - eq, ne, in, notIn
 *
 * @example
 * ```php
 * // Find switch stack by name
 * SwitchStackFilter::name()->like('Stack-*')
 *
 * // Find stacks containing a specific switch (by MAC address)
 * SwitchStackFilter::unitsMacAddress()->contains('94:2a:6f:26:c6:ca')
 *
 * // Find stacks containing unit 1 and unit 2
 * SwitchStackFilter::unitsId()->containsAll([1, 2])
 * ```
 */
class SwitchStackFilter extends Filter
{
    /**
     * Filter by switch stack ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by switch stack name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by stack unit IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function unitsId(): static
    {
        return static::where('units.id');
    }

    /**
     * Filter by stack unit MAC addresses (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function unitsMacAddress(): static
    {
        return static::where('units.macAddress');
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
