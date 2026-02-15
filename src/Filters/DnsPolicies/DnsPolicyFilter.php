<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\DnsPolicies;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * DNS Policy Filter Builder
 *
 * Provides a type-safe, fluent interface for building DNS policy filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - type (STRING) - eq, ne, in, notIn
 * - id (UUID) - eq, ne, in, notIn
 * - enabled (BOOLEAN) - eq, ne
 * - domain (STRING) - eq, ne, in, notIn, like
 * - ipv4Address (STRING) - eq, ne, in, notIn
 * - ipv6Address (STRING) - eq, ne, in, notIn
 * - targetDomain (STRING) - eq, ne, in, notIn, like
 * - mailServerDomain (STRING) - eq, ne, in, notIn, like
 * - text (STRING) - eq, ne, in, notIn, like
 * - serverDomain (STRING) - eq, ne, in, notIn, like
 * - ipAddress (STRING) - eq, ne, in, notIn
 * - ttlSeconds (INTEGER) - eq, ne, gt, ge, lt, le
 * - priority (INTEGER) - eq, ne, gt, ge, lt, le
 * - service (STRING) - eq, ne, in, notIn
 * - protocol (STRING) - eq, ne, in, notIn
 * - port (INTEGER) - eq, ne, gt, ge, lt, le
 * - weight (INTEGER) - eq, ne, gt, ge, lt, le
 *
 * @example
 * ```php
 * // Find A records
 * DnsPolicyFilter::type()->equals('A')
 *
 * // Find enabled policies for a domain
 * DnsPolicyFilter::and(
 *     DnsPolicyFilter::enabled()->equals(true),
 *     DnsPolicyFilter::domain()->like('*.example.com')
 * )
 * ```
 */
class DnsPolicyFilter extends Filter
{
    /**
     * Filter by record type (A, AAAA, CNAME, MX, TXT, SRV, FORWARD_DOMAIN)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function type(): static
    {
        return static::where('type');
    }

    /**
     * Filter by policy ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
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
     * Filter by domain name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function domain(): static
    {
        return static::where('domain');
    }

    /**
     * Filter by IPv4 address
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function ipv4Address(): static
    {
        return static::where('ipv4Address');
    }

    /**
     * Filter by IPv6 address
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function ipv6Address(): static
    {
        return static::where('ipv6Address');
    }

    /**
     * Filter by target domain (CNAME records)
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function targetDomain(): static
    {
        return static::where('targetDomain');
    }

    /**
     * Filter by mail server domain (MX records)
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function mailServerDomain(): static
    {
        return static::where('mailServerDomain');
    }

    /**
     * Filter by text content (TXT records)
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function text(): static
    {
        return static::where('text');
    }

    /**
     * Filter by server domain (SRV records)
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function serverDomain(): static
    {
        return static::where('serverDomain');
    }

    /**
     * Filter by IP address
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function ipAddress(): static
    {
        return static::where('ipAddress');
    }

    /**
     * Filter by TTL in seconds
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function ttlSeconds(): static
    {
        return static::where('ttlSeconds');
    }

    /**
     * Filter by priority (MX/SRV records)
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function priority(): static
    {
        return static::where('priority');
    }

    /**
     * Filter by service name (SRV records)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function service(): static
    {
        return static::where('service');
    }

    /**
     * Filter by protocol (SRV records)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function protocol(): static
    {
        return static::where('protocol');
    }

    /**
     * Filter by port number (SRV records)
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function port(): static
    {
        return static::where('port');
    }

    /**
     * Filter by weight (SRV records)
     *
     * Supported operators: eq, ne, gt, ge, lt, le
     */
    public static function weight(): static
    {
        return static::where('weight');
    }

    // ===== Preset Filters =====

    /**
     * Filter for A records only
     */
    public static function aRecords(): static
    {
        return static::type()->equals('A');
    }

    /**
     * Filter for AAAA records only
     */
    public static function aaaaRecords(): static
    {
        return static::type()->equals('AAAA');
    }

    /**
     * Filter for CNAME records only
     */
    public static function cnameRecords(): static
    {
        return static::type()->equals('CNAME');
    }

    /**
     * Filter for MX records only
     */
    public static function mxRecords(): static
    {
        return static::type()->equals('MX');
    }

    /**
     * Filter for TXT records only
     */
    public static function txtRecords(): static
    {
        return static::type()->equals('TXT');
    }

    /**
     * Filter for SRV records only
     */
    public static function srvRecords(): static
    {
        return static::type()->equals('SRV');
    }

    /**
     * Filter for forward domain entries only
     */
    public static function forwardDomains(): static
    {
        return static::type()->equals('FORWARD_DOMAIN');
    }

    /**
     * Filter for enabled policies only
     */
    public static function enabledOnly(): static
    {
        return static::enabled()->equals(true);
    }
}
