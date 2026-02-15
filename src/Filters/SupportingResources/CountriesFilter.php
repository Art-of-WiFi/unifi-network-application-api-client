<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Countries Filter Builder
 *
 * Provides a type-safe, fluent interface for building country filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - code (STRING) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 *
 * @example
 * ```php
 * // Find a specific country by code
 * CountriesFilter::code()->equals('US')
 *
 * // Find countries by name pattern
 * CountriesFilter::name()->like('United*')
 *
 * // Find multiple countries by code
 * CountriesFilter::code()->in(['US', 'GB', 'CA', 'AU'])
 *
 * // Find countries with "Kingdom" in the name
 * CountriesFilter::name()->like('*Kingdom*')
 * ```
 */
class CountriesFilter extends Filter
{
    /**
     * Filter by country code (ISO standard)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function code(): static
    {
        return static::where('code');
    }

    /**
     * Filter by country name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    // ===== Preset Filters =====

    /**
     * Filter for United States
     */
    public static function unitedStates(): static
    {
        return static::code()->equals('US');
    }

    /**
     * Filter for United Kingdom
     */
    public static function unitedKingdom(): static
    {
        return static::code()->equals('GB');
    }

    /**
     * Filter for Canada
     */
    public static function canada(): static
    {
        return static::code()->equals('CA');
    }

    /**
     * Filter for Australia
     */
    public static function australia(): static
    {
        return static::code()->equals('AU');
    }

    /**
     * Filter for European Union countries (common ones)
     */
    public static function europeanUnion(): static
    {
        return static::code()->in([
            'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR',
            'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
            'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE',
        ]);
    }

    /**
     * Filter for North American countries
     */
    public static function northAmerica(): static
    {
        return static::code()->in(['US', 'CA', 'MX']);
    }
}
