<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * DPI Applications Filter Builder
 *
 * Provides a type-safe, fluent interface for building DPI application filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (INTEGER) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 *
 * @example
 * ```php
 * // Find application by name
 * DpiApplicationsFilter::name()->equals('Netflix')
 *
 * // Find applications with name pattern
 * DpiApplicationsFilter::name()->like('*Tube*')  // Matches YouTube, etc.
 *
 * // Find specific applications by ID
 * DpiApplicationsFilter::id()->in([100, 101, 102])
 * ```
 */
class DpiApplicationsFilter extends Filter
{
    /**
     * Filter by application ID
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by application name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    // ===== Preset Filters =====

    /**
     * Filter for Netflix
     */
    public static function netflix(): static
    {
        return static::name()->equals('Netflix');
    }

    /**
     * Filter for YouTube
     */
    public static function youtube(): static
    {
        return static::name()->equals('YouTube');
    }

    /**
     * Filter for Facebook
     */
    public static function facebook(): static
    {
        return static::name()->equals('Facebook');
    }

    /**
     * Filter for streaming services
     */
    public static function streamingServices(): static
    {
        return static::or(
            static::name()->like('*Netflix*'),
            static::name()->like('*YouTube*'),
            static::name()->like('*Prime*'),
            static::name()->like('*Disney*'),
            static::name()->like('*Hulu*')
        );
    }
}
