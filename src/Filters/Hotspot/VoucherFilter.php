<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Hotspot;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Voucher Filter Builder
 *
 * Provides a type-safe, fluent interface for building hotspot voucher filters.
 * Can be used for both listing and deleting vouchers.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - createdAt (TIMESTAMP) - eq, ne, gt, ge, lt, le
 * - name (STRING) - eq, ne, in, notIn, like
 * - code (STRING) - eq, ne, in, notIn
 * - authorizedGuestLimit (INTEGER) - isNull, isNotNull, eq, ne, gt, ge, lt, le
 * - authorizedGuestCount (INTEGER) - eq, ne, gt, ge, lt, le
 * - activatedAt (TIMESTAMP) - eq, ne, gt, ge, lt, le
 * - expiresAt (TIMESTAMP) - eq, ne, gt, ge, lt, le
 * - expired (BOOLEAN) - eq, ne
 * - timeLimitMinutes (INTEGER) - eq, ne, gt, ge, lt, le
 * - dataUsageLimitMBytes (INTEGER) - isNull, isNotNull, eq, ne, gt, ge, lt, le
 * - rxRateLimitKbps (INTEGER) - isNull, isNotNull, eq, ne, gt, ge, lt, le
 * - txRateLimitKbps (INTEGER) - isNull, isNotNull, eq, ne, gt, ge, lt, le
 *
 * Timestamp properties accept DateTimeInterface values.
 *
 * @example
 * ```php
 * // Find vouchers by name
 * VoucherFilter::name()->like('Event*')
 *
 * // Find active (non-expired) vouchers
 * VoucherFilter::active()
 *
 * // Find voucher by code
 * VoucherFilter::code()->equals('ABC123')
 *
 * // Find vouchers created in the last 24 hours
 * VoucherFilter::createdAt()->greaterThan(new DateTimeImmutable('-24 hours'))
 *
 * // Find unused vouchers
 * VoucherFilter::authorizedGuestCount()->equals(0)
 * ```
 */
class VoucherFilter extends Filter
{
    /**
     * Filter by voucher ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by creation timestamp
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function createdAt(): static
    {
        return static::where('createdAt');
    }

    /**
     * Filter by voucher name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by voucher code
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function code(): static
    {
        return static::where('code');
    }

    /**
     * Filter by the limit of guests that can use the voucher
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le
     */
    public static function authorizedGuestLimit(): static
    {
        return static::where('authorizedGuestLimit');
    }

    /**
     * Filter by the number of guests that used the voucher
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function authorizedGuestCount(): static
    {
        return static::where('authorizedGuestCount');
    }

    /**
     * Filter by activation timestamp
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function activatedAt(): static
    {
        return static::where('activatedAt');
    }

    /**
     * Filter by expiration timestamp
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function expiresAt(): static
    {
        return static::where('expiresAt');
    }

    /**
     * Filter by expired status
     *
     * Supported operators: eq, ne
     */
    public static function expired(): static
    {
        return static::where('expired');
    }

    /**
     * Filter by time limit in minutes
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function timeLimitMinutes(): static
    {
        return static::where('timeLimitMinutes');
    }

    /**
     * Filter by data usage limit in megabytes
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le
     */
    public static function dataUsageLimitMBytes(): static
    {
        return static::where('dataUsageLimitMBytes');
    }

    /**
     * Filter by download rate limit in kbps
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le
     */
    public static function rxRateLimitKbps(): static
    {
        return static::where('rxRateLimitKbps');
    }

    /**
     * Filter by upload rate limit in kbps
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le
     */
    public static function txRateLimitKbps(): static
    {
        return static::where('txRateLimitKbps');
    }

    // ===== Preset Filters =====

    /**
     * Filter for active (non-expired) vouchers only
     */
    public static function active(): static
    {
        return static::expired()->equals(false);
    }

    /**
     * Filter for expired vouchers only
     */
    public static function expiredOnly(): static
    {
        return static::expired()->equals(true);
    }

    /**
     * Filter for vouchers that have not been used by any guest yet
     */
    public static function unused(): static
    {
        return static::authorizedGuestCount()->equals(0);
    }
}
