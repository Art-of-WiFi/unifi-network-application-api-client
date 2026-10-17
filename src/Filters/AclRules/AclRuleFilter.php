<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters\AclRules;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;

/**
 * ACL Rule Filter Builder
 *
 * Provides a type-safe, fluent interface for building ACL rule filters.
 *
 * Filterable Properties (per UniFi API specification):
 * - type (STRING) - eq, ne, in, notIn (Values: IPV4, MAC)
 * - id (UUID) - eq, ne, in, notIn
 * - enabled (BOOLEAN) - eq, ne
 * - name (STRING) - eq, ne, in, notIn, like
 * - description (STRING) - isNull, isNotNull, eq, ne, in, notIn, like
 * - action (STRING) - eq, ne, in, notIn (Values: ALLOW, BLOCK)
 * - index (INTEGER) - eq, ne, gt, ge, lt, le, in, notIn
 * - protocolFilter (SET(STRING)) - isNull, isNotNull, contains, containsAny, containsAll, containsExactly
 * - networkId (UUID) - isNull, isNotNull, eq, ne, in, notIn
 * - enforcingDeviceFilter.deviceIds (SET(UUID)) - isNull, isNotNull, contains, containsAny, containsAll, containsExactly
 * - metadata.origin (STRING) - eq, ne, in, notIn
 * - sourceFilter.type (STRING) - isNull, isNotNull, eq, ne, in, notIn
 * - sourceFilter.ipAddressesOrSubnets (SET(STRING)) - contains, containsAny, containsAll, containsExactly
 * - sourceFilter.portFilter (SET(INTEGER)) - isNull, isNotNull, contains, containsAny, containsAll, containsExactly
 * - sourceFilter.networkIds (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 * - sourceFilter.macAddresses (SET(STRING)) - contains, containsAny, containsAll, containsExactly
 * - sourceFilter.prefixLength (INTEGER) - isNull, isNotNull, eq, ne, gt, ge, lt, le, in, notIn
 * - destinationFilter.type (STRING) - isNull, isNotNull, eq, ne, in, notIn
 * - destinationFilter.ipAddressesOrSubnets (SET(STRING)) - contains, containsAny, containsAll, containsExactly
 * - destinationFilter.portFilter (SET(INTEGER)) - isNull, isNotNull, contains, containsAny, containsAll, containsExactly
 * - destinationFilter.networkIds (SET(UUID)) - contains, containsAny, containsAll, containsExactly
 * - destinationFilter.macAddresses (SET(STRING)) - contains, containsAny, containsAll, containsExactly
 * - destinationFilter.prefixLength (INTEGER) - isNull, isNotNull, eq, ne, gt, ge, lt, le, in, notIn
 *
 * @example
 * ```php
 * // Find ACL rules by name
 * AclRuleFilter::name()->like('Block*')
 *
 * // Find enabled rules
 * AclRuleFilter::enabled()->equals(true)
 *
 * // Find rules with specific action
 * AclRuleFilter::action()->equals('BLOCK')
 *
 * // Find rules that match destination port 443
 * AclRuleFilter::destinationPortFilter()->contains(443)
 * ```
 */
class AclRuleFilter extends Filter
{
    /**
     * Filter by ACL rule ID (UUID)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function id(): static
    {
        return static::where('id');
    }

    /**
     * Filter by ACL rule name
     *
     * Supported operators: eq, ne, in, notIn, like
     */
    public static function name(): static
    {
        return static::where('name');
    }

    /**
     * Filter by ACL rule type (IPV4 or MAC)
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
     * Filter by action (ALLOW or BLOCK)
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function action(): static
    {
        return static::where('action');
    }

    /**
     * Filter by description
     *
     * Supported operators: isNull, isNotNull, eq, ne, in, notIn, like
     */
    public static function description(): static
    {
        return static::where('description');
    }

    /**
     * Filter by rule index (lower index has higher priority)
     *
     * Supported operators: eq, ne, gt, ge, lt, le, in, notIn
     */
    public static function index(): static
    {
        return static::where('index');
    }

    /**
     * Filter by protocols the rule applies to (set operations, values: TCP, UDP)
     *
     * Supported operators: isNull, isNotNull, contains, containsAny, containsAll, containsExactly
     */
    public static function protocolFilter(): static
    {
        return static::where('protocolFilter');
    }

    /**
     * Filter by network ID (UUID)
     *
     * Supported operators: isNull, isNotNull, eq, ne, in, notIn
     */
    public static function networkId(): static
    {
        return static::where('networkId');
    }

    /**
     * Filter by IDs of the devices enforcing the rule (set operations)
     *
     * Supported operators: isNull, isNotNull, contains, containsAny, containsAll, containsExactly
     */
    public static function enforcingDeviceIds(): static
    {
        return static::where('enforcingDeviceFilter.deviceIds');
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

    // ===== Source Filter =====

    /**
     * Filter by source filter type
     *
     * Supported operators: isNull, isNotNull, eq, ne, in, notIn
     */
    public static function sourceFilterType(): static
    {
        return static::where('sourceFilter.type');
    }

    /**
     * Filter by source IP addresses or subnets (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function sourceIpAddressesOrSubnets(): static
    {
        return static::where('sourceFilter.ipAddressesOrSubnets');
    }

    /**
     * Filter by source ports (set operations)
     *
     * Supported operators: isNull, isNotNull, contains, containsAny, containsAll, containsExactly
     */
    public static function sourcePortFilter(): static
    {
        return static::where('sourceFilter.portFilter');
    }

    /**
     * Filter by source network IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function sourceNetworkIds(): static
    {
        return static::where('sourceFilter.networkIds');
    }

    /**
     * Filter by source MAC addresses (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function sourceMacAddresses(): static
    {
        return static::where('sourceFilter.macAddresses');
    }

    /**
     * Filter by source MAC address prefix length
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le, in, notIn
     */
    public static function sourcePrefixLength(): static
    {
        return static::where('sourceFilter.prefixLength');
    }

    // ===== Destination Filter =====

    /**
     * Filter by destination filter type
     *
     * Supported operators: isNull, isNotNull, eq, ne, in, notIn
     */
    public static function destinationFilterType(): static
    {
        return static::where('destinationFilter.type');
    }

    /**
     * Filter by destination IP addresses or subnets (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function destinationIpAddressesOrSubnets(): static
    {
        return static::where('destinationFilter.ipAddressesOrSubnets');
    }

    /**
     * Filter by destination ports (set operations)
     *
     * Supported operators: isNull, isNotNull, contains, containsAny, containsAll, containsExactly
     */
    public static function destinationPortFilter(): static
    {
        return static::where('destinationFilter.portFilter');
    }

    /**
     * Filter by destination network IDs (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function destinationNetworkIds(): static
    {
        return static::where('destinationFilter.networkIds');
    }

    /**
     * Filter by destination MAC addresses (set operations)
     *
     * Supported operators: contains, containsAny, containsAll, containsExactly
     */
    public static function destinationMacAddresses(): static
    {
        return static::where('destinationFilter.macAddresses');
    }

    /**
     * Filter by destination MAC address prefix length
     *
     * Supported operators: isNull, isNotNull, eq, ne, gt, ge, lt, le, in, notIn
     */
    public static function destinationPrefixLength(): static
    {
        return static::where('destinationFilter.prefixLength');
    }

    // ===== Preset Filters =====

    /**
     * Filter for enabled ACL rules only
     */
    public static function enabledOnly(): static
    {
        return static::enabled()->equals(true);
    }

    /**
     * Filter for disabled ACL rules only
     */
    public static function disabledOnly(): static
    {
        return static::enabled()->equals(false);
    }
}
