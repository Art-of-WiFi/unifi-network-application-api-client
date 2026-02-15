<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Clients;

use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientAccessType;
use ArtOfWiFi\UnifiNetworkApplicationApi\Enums\ClientType;
use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Client Filter Builder
 *
 * Provides a type-safe, fluent interface for building client filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - type (STRING) - eq, ne, in, notIn (Values: WIRED, WIRELESS, VPN, TELEPORT)
 * - macAddress (STRING) - isNull, isNotNull, eq, ne, in, notIn
 * - ipAddress (STRING) - isNull, isNotNull, eq, ne, in, notIn
 * - connectedAt (TIMESTAMP) - isNull, isNotNull, eq, ne, gt, ge, lt, le
 * - access.type (STRING) - eq, ne, in, notIn (Values: DEFAULT, GUEST)
 * - access.authorized (BOOLEAN) - isNull, isNotNull, eq, ne
 *
 * @example
 * ```php
 * // Find wireless clients
 * ClientFilter::type()->equals(ClientType::WIRELESS)
 *
 * // Find guest clients
 * ClientFilter::accessType()->equals(ClientAccessType::GUEST)
 *
 * // Find wireless guests
 * ClientFilter::and(
 *     ClientFilter::type()->equals(ClientType::WIRELESS),
 *     ClientFilter::accessType()->equals(ClientAccessType::GUEST)
 * )
 *
 * // Find clients with IP addresses
 * ClientFilter::ipAddress()->isNotNull()
 * ```
 */
class ClientFilter extends Filter
{
    /**
     * Filter by client ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by client connection type
     *
     * Supported operators: eq, ne, in, notIn
     * Values: WIRED, WIRELESS, VPN, TELEPORT
     */
    public static function type(): static
    {
        return static::where('type');
    }

    /**
     * Filter by client MAC address
     *
     * Supported operators: isNull, isNotNull, eq, ne, in, notIn
     */
    public static function macAddress(): static
    {
        return static::where('macAddress');
    }

    /**
     * Filter by client IP address
     *
     * Supported operators: isNull, isNotNull, eq, ne, in, notIn
     */
    public static function ipAddress(): static
    {
        return static::where('ipAddress');
    }

    /**
     * Filter by connection timestamp
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le
     */
    public static function connectedAt(): static
    {
        return static::where('connectedAt');
    }

    /**
     * Filter by access type (DEFAULT or GUEST)
     *
     * Supported operators: eq, ne, in, notIn
     * Values: DEFAULT, GUEST
     */
    public static function accessType(): static
    {
        return static::where('access.type');
    }

    /**
     * Filter by authorization status
     *
     * Supported operators: isNull, isNotNull, eq, ne
     */
    public static function accessAuthorized(): static
    {
        return static::where('access.authorized');
    }

    // ===== Preset Filters =====

    /**
     * Filter for wireless clients only
     */
    public static function wireless(): static
    {
        return static::type()->equals(ClientType::WIRELESS);
    }

    /**
     * Filter for wired clients only
     */
    public static function wired(): static
    {
        return static::type()->equals(ClientType::WIRED);
    }

    /**
     * Filter for VPN clients only
     */
    public static function vpn(): static
    {
        return static::type()->equals(ClientType::VPN);
    }

    /**
     * Filter for Teleport clients only
     */
    public static function teleport(): static
    {
        return static::type()->equals(ClientType::TELEPORT);
    }

    /**
     * Filter for guest clients only
     */
    public static function guests(): static
    {
        return static::accessType()->equals(ClientAccessType::GUEST);
    }

    /**
     * Filter for default/standard access clients only
     */
    public static function defaultAccess(): static
    {
        return static::accessType()->equals(ClientAccessType::DEFAULT);
    }

    /**
     * Filter for wireless guest clients
     */
    public static function wirelessGuests(): static
    {
        return static::and(
            static::type()->equals(ClientType::WIRELESS),
            static::accessType()->equals(ClientAccessType::GUEST)
        );
    }

    /**
     * Filter for wired guest clients
     */
    public static function wiredGuests(): static
    {
        return static::and(
            static::type()->equals(ClientType::WIRED),
            static::accessType()->equals(ClientAccessType::GUEST)
        );
    }

    /**
     * Filter for authorized guest clients
     */
    public static function authorizedGuests(): static
    {
        return static::and(
            static::accessType()->equals(ClientAccessType::GUEST),
            static::accessAuthorized()->equals(true)
        );
    }

    /**
     * Filter for unauthorized guest clients
     */
    public static function unauthorizedGuests(): static
    {
        return static::and(
            static::accessType()->equals(ClientAccessType::GUEST),
            static::accessAuthorized()->equals(false)
        );
    }

    /**
     * Filter for clients with assigned IP addresses
     */
    public static function withIpAddress(): static
    {
        return static::ipAddress()->isNotNull();
    }

    /**
     * Filter for clients without IP addresses
     */
    public static function withoutIpAddress(): static
    {
        return static::ipAddress()->isNull();
    }
}
