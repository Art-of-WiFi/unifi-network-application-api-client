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
 * - id (UUID) - eq, ne, in, notIn
 * - name (STRING) - eq, ne, in, notIn, like
 * - type (STRING) - eq, ne, in, notIn
 * - enabled (BOOLEAN) - eq, ne
 * - action (STRING) - eq, ne, in, notIn
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
     * Filter by ACL rule type
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
     * Filter by action
     *
     * Supported operators: eq, ne, in, notIn
     */
    public static function action(): static
    {
        return static::where('action');
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
