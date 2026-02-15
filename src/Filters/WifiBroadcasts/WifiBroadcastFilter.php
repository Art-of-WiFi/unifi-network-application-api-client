<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\WifiBroadcasts;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * WiFi Broadcast Filter Builder
 *
 * Provides a type-safe, fluent interface for building WiFi broadcast (SSID) filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - type (STRING) - eq, ne, in, notIn
 * - enabled (BOOLEAN) - eq, ne
 *
 * @example
 * ```php
 * // Find WiFi broadcast by name
 * WifiBroadcastFilter::name()->like('Guest*')
 *
 * // Find enabled broadcasts
 * WifiBroadcastFilter::enabled()->equals(true)
 *
 * // Use preset filter
 * WifiBroadcastFilter::enabledOnly()
 * ```
 */
class WifiBroadcastFilter extends Filter
{
    /**
     * Filter by WiFi broadcast ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by WiFi broadcast name (SSID)
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by WiFi broadcast type
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function type(): static
    {
        return static::where('type');
    }

    /**
     * Filter by enabled status
     *
     * Supported operators: eq, ne
     */
    public static function enabled(): static
    {
        return static::where('enabled');
    }

    // ===== Preset Filters =====

    /**
     * Filter for enabled WiFi broadcasts only
     */
    public static function enabledOnly(): static
    {
        return static::enabled()->equals(true);
    }

    /**
     * Filter for disabled WiFi broadcasts only
     */
    public static function disabledOnly(): static
    {
        return static::enabled()->equals(false);
    }
}
