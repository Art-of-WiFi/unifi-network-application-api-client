<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Hotspot;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * Voucher Filter Builder
 *
 * Provides a type-safe, fluent interface for building hotspot voucher filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - code (STRING) - eq, ne, in, notIn
 * - expired (BOOLEAN) - eq, ne
 *
 * @example
 * ```php
 * // Find vouchers by name
 * VoucherFilter::name()->like('Event*')
 *
 * // Find active (non-expired) vouchers
 * VoucherFilter::active()
 *
 * // Find expired vouchers
 * VoucherFilter::expired()->equals(true)
 *
 * // Find voucher by code
 * VoucherFilter::code()->equals('ABC123')
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
     * Filter by expired status
     *
     * Supported operators: eq, ne
     */
    public static function expired(): static
    {
        return static::where('expired');
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
}
