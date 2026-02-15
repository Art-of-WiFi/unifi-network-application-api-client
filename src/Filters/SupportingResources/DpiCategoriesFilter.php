<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * DPI Categories Filter Builder
 *
 * Provides a type-safe, fluent interface for building DPI category filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (INTEGER) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 *
 * @example
 * ```php
 * // Find category by name
 * DpiCategoriesFilter::name()->equals('Social Networking')
 *
 * // Find categories with name pattern
 * DpiCategoriesFilter::name()->like('*Gaming*')
 *
 * // Find specific categories by ID
 * DpiCategoriesFilter::id()->in([1, 2, 3])
 * ```
 */
class DpiCategoriesFilter extends Filter
{
    /**
     * Filter by category ID
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by category name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    // ===== Preset Filters =====

    /**
     * Filter for social media categories
     */
    public static function socialMedia(): static
    {
        return static::name()->like('*Social*');
    }

    /**
     * Filter for streaming categories
     */
    public static function streaming(): static
    {
        return static::name()->like('*Streaming*');
    }

    /**
     * Filter for gaming categories
     */
    public static function gaming(): static
    {
        return static::name()->like('*Gam*');
    }
}
