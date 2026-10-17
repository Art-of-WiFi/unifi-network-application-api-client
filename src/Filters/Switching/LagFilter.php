<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Switching;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * LAG (Link Aggregation Group) Filter Builder
 *
 * Provides a type-safe, fluent interface for building LAG filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - type (STRING) - eq, ne, in, notIn
 * - switchStackId (UUID) - eq, ne, in, notIn, isNull, isNotNull
 * - mcLagDomainId (UUID) - eq, ne, in, notIn, isNull, isNotNull
 * - members.deviceId (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 * - members.portIdxs (SET(INTEGER)) - contains, containsAny, containsAll, containsExactly
 * - metadata.origin (STRING) - eq, ne, in, notIn
 *
 * LAG types: LOCAL (single switch), SWITCH_STACK and MULTI_CHASSIS (MC-LAG)
 *
 * @example
 * ```php
 * // Find LAGs by type
 * LagFilter::type()->equals('SWITCH_STACK')
 *
 * // Find LAGs that include port 5
 * LagFilter::membersPortIdxs()->contains(5)
 *
 * // Find LAGs belonging to a specific switch stack
 * LagFilter::switchStackId()->equals('550e8400-e29b-41d4-a716-446655440000')
 *
 * // Find MC-LAG type LAGs
 * LagFilter::mcLagDomainId()->isNotNull()
 *
 * // Find LAGs containing a specific device
 * LagFilter::membersDeviceId()->contains('550e8400-e29b-41d4-a716-446655440000')
 * ```
 */
class LagFilter extends Filter
{
    /**
     * Filter by LAG ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by LAG type (LOCAL, SWITCH_STACK or MULTI_CHASSIS)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function type(): static
    {
        return static::where('type');
    }

    /**
     * Filter by switch stack ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn, isNull, isNotNull
     */
    public static function switchStackId(): static
    {
        return static::where('switchStackId');
    }

    /**
     * Filter by MC-LAG domain ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn, isNull, isNotNull
     */
    public static function mcLagDomainId(): static
    {
        return static::where('mcLagDomainId');
    }

    /**
     * Filter by member device IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function membersDeviceId(): static
    {
        return static::where('members.deviceId');
    }

    /**
     * Filter by member port indexes (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function membersPortIdxs(): static
    {
        return static::where('members.portIdxs');
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

    // ===== Preset Filters =====

    /**
     * Filter for local (single switch) LAGs only
     */
    public static function localLags(): static
    {
        return static::type()->equals('LOCAL');
    }

    /**
     * Filter for switch stack LAGs only
     */
    public static function switchStackLags(): static
    {
        return static::type()->equals('SWITCH_STACK');
    }

    /**
     * Filter for MC-LAG (multi-chassis) LAGs only
     */
    public static function mcLags(): static
    {
        return static::type()->equals('MULTI_CHASSIS');
    }
}
