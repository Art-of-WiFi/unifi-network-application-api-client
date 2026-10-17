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
 * - type (STRING) - eq, ne, in, notIn (Values: STANDARD, IOT_OPTIMIZED)
 * - id (UUID) - eq, ne, in, notIn
 * - enabled (BOOLEAN) - eq, ne
 * - name (STRING) - eq, ne, in, notIn, like
 * - broadcastingFrequenciesGHz (SET(DECIMAL)) - contains, containsAny, containsAll, containsExactly
 * - metadata.origin (STRING) - eq, ne, in, notIn
 * - network.type (STRING) - eq, ne, in, notIn, isNull, isNotNull (Values: NATIVE, SPECIFIC)
 * - network.networkId (UUID) - eq, ne, in, notIn
 * - securityConfiguration.type (STRING) - eq, ne, in, notIn
 * - broadcastingDeviceFilter.type (STRING) - eq, ne, in, notIn, isNull, isNotNull (Values: DEVICES, DEVICE_TAGS)
 * - broadcastingDeviceFilter.deviceIds (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 * - broadcastingDeviceFilter.deviceTagIds (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 * - hotspotConfiguration.type (STRING) - eq, ne, in, notIn (Values: CAPTIVE_PORTAL, PASSPOINT)
 *
 * @example
 * ```php
 * // Find WiFi broadcast by name
 * WifiBroadcastFilter::name()->like('Guest*')
 *
 * // Find enabled broadcasts
 * WifiBroadcastFilter::enabled()->equals(true)
 *
 * // Find broadcasts on the 6 GHz band
 * WifiBroadcastFilter::broadcastingFrequenciesGHz()->contains(6)
 *
 * // Find WPA2/WPA3 personal broadcasts
 * WifiBroadcastFilter::securityType()->equals('WPA2_WPA3_PERSONAL')
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
     * Filter by WiFi broadcast type (STANDARD or IOT_OPTIMIZED)
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

    /**
     * Filter by broadcasting frequencies in GHz (set operations, values: 2.4, 5, 6)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function broadcastingFrequenciesGHz(): static
    {
        return static::where('broadcastingFrequenciesGHz');
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

    /**
     * Filter by network reference type (NATIVE or SPECIFIC)
     *
     * Supported operators: eq, ne, in, notIn, isNull, isNotNull
     */
    public static function networkType(): static
    {
        return static::where('network.type');
    }

    /**
     * Filter by network ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function networkId(): static
    {
        return static::where('network.networkId');
    }

    /**
     * Filter by security configuration type (e.g. OPEN, WPA2_PERSONAL, WPA2_WPA3_PERSONAL)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function securityType(): static
    {
        return static::where('securityConfiguration.type');
    }

    /**
     * Filter by broadcasting device filter type (DEVICES or DEVICE_TAGS)
     *
     * Supported operators: eq, ne, in, notIn, isNull, isNotNull
     */
    public static function broadcastingDeviceFilterType(): static
    {
        return static::where('broadcastingDeviceFilter.type');
    }

    /**
     * Filter by IDs of the devices broadcasting the WiFi network (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function broadcastingDeviceIds(): static
    {
        return static::where('broadcastingDeviceFilter.deviceIds');
    }

    /**
     * Filter by IDs of the device tags broadcasting the WiFi network (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function broadcastingDeviceTagIds(): static
    {
        return static::where('broadcastingDeviceFilter.deviceTagIds');
    }

    /**
     * Filter by hotspot configuration type (CAPTIVE_PORTAL or PASSPOINT)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function hotspotType(): static
    {
        return static::where('hotspotConfiguration.type');
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
