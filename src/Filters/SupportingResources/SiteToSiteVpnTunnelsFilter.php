<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Site-to-Site VPN Tunnels Filter Builder
 *
 * Provides a type-safe, fluent interface for building site-to-site VPN tunnel filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - type (STRING) - eq, ne, in, notIn
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - metadata.origin (STRING) - eq, ne, in, notIn
 * - metadata.source (STRING) - eq, ne, in, notIn, isNull, isNotNull
 *
 * @example
 * ```php
 * // Find tunnel by name
 * SiteToSiteVpnTunnelsFilter::name()->equals('Office-to-AWS')
 *
 * // Find tunnels by type
 * SiteToSiteVpnTunnelsFilter::type()->equals('L2TP')
 *
 * // Find tunnels with source
 * SiteToSiteVpnTunnelsFilter::metadataSource()->isNotNull()
 * ```
 */
class SiteToSiteVpnTunnelsFilter extends Filter
{
    /**
     * Filter by VPN tunnel type
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function type(): static
    {
        return static::where('type');
    }

    /**
     * Filter by tunnel ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by tunnel name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
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
     * Filter by metadata source
     *
     * Supported operators: eq, ne, in, notIn, isNull, isNotNull
     */
    public static function metadataSource(): static
    {
        return static::where('metadata.source');
    }
}
