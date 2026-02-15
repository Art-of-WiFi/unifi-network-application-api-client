<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Filters;

/**
 * Base Filter Builder
 *
 * Provides a fluent, type-safe interface for building UniFi API filters.
 * This class should be extended by resource-specific filter classes.
 *
 * @phpstan-consistent-constructor
 *
 * @example
 * ```php
 * // Simple filter
 * DeviceFilter::where('name')->like('AP-*')
 *
 * // Complex filter with AND
 * DeviceFilter::and(
 *     DeviceFilter::model()->equals('U6-LR'),
 *     DeviceFilter::firmwareVersion()->greaterThan('6.0.0')
 * )
 *
 * // Complex filter with OR
 * DeviceFilter::or(
 *     DeviceFilter::name()->like('AP-*'),
 *     DeviceFilter::name()->like('USW-*')
 * )
 * ```
 */
abstract class Filter
{
    protected array $conditions = [];

    protected ?string $currentProperty = null;

    /**
     * Start a new filter on a property
     *
     * @param  string  $property  The property to filter on
     */
    public static function where(string $property): static
    {
        $instance = new static; // @phpstan-ignore new.static
        $instance->currentProperty = $property;

        return $instance;
    }

    /**
     * Combine multiple filters with AND logic
     *
     * @param  Filter  ...$filters  Filters to combine
     */
    public static function and(Filter ...$filters): static
    {
        $instance = new static; // @phpstan-ignore new.static
        $instance->conditions[] = [
            'type' => 'logical',
            'operator' => 'and',
            'filters' => $filters,
        ];

        return $instance;
    }

    /**
     * Combine multiple filters with OR logic
     *
     * @param  Filter  ...$filters  Filters to combine
     */
    public static function or(Filter ...$filters): static
    {
        $instance = new static; // @phpstan-ignore new.static
        $instance->conditions[] = [
            'type' => 'logical',
            'operator' => 'or',
            'filters' => $filters,
        ];

        return $instance;
    }

    /**
     * Equals operator (eq)
     *
     * @param  string|int|bool|\BackedEnum  $value  Value to compare
     */
    public function equals(string|int|bool|\BackedEnum $value): static
    {
        return $this->addCondition('eq', $value);
    }

    /**
     * Shorthand for equals()
     *
     * @param  string|int|bool|\BackedEnum  $value  Value to compare
     */
    public function eq(string|int|bool|\BackedEnum $value): static
    {
        return $this->equals($value);
    }

    /**
     * Not equals operator (ne)
     *
     * @param  string|int|bool|\BackedEnum  $value  Value to compare
     */
    public function notEquals(string|int|bool|\BackedEnum $value): static
    {
        return $this->addCondition('ne', $value);
    }

    /**
     * Shorthand for notEquals()
     *
     * @param  string|int|bool|\BackedEnum  $value  Value to compare
     */
    public function ne(string|int|bool|\BackedEnum $value): static
    {
        return $this->notEquals($value);
    }

    /**
     * Greater than operator (gt)
     *
     * @param  string|int  $value  Value to compare
     */
    public function greaterThan(string|int $value): static
    {
        return $this->addCondition('gt', $value);
    }

    /**
     * Shorthand for greaterThan()
     *
     * @param  string|int  $value  Value to compare
     */
    public function gt(string|int $value): static
    {
        return $this->greaterThan($value);
    }

    /**
     * Greater than or equal operator (ge)
     *
     * @param  string|int  $value  Value to compare
     */
    public function greaterThanOrEqual(string|int $value): static
    {
        return $this->addCondition('ge', $value);
    }

    /**
     * Shorthand for greaterThanOrEqual()
     *
     * @param  string|int  $value  Value to compare
     */
    public function gte(string|int $value): static
    {
        return $this->greaterThanOrEqual($value);
    }

    /**
     * Less than operator (lt)
     *
     * @param  string|int  $value  Value to compare
     */
    public function lessThan(string|int $value): static
    {
        return $this->addCondition('lt', $value);
    }

    /**
     * Shorthand for lessThan()
     *
     * @param  string|int  $value  Value to compare
     */
    public function lt(string|int $value): static
    {
        return $this->lessThan($value);
    }

    /**
     * Less than or equal operator (le)
     *
     * @param  string|int  $value  Value to compare
     */
    public function lessThanOrEqual(string|int $value): static
    {
        return $this->addCondition('le', $value);
    }

    /**
     * Shorthand for lessThanOrEqual()
     *
     * @param  string|int  $value  Value to compare
     */
    public function lte(string|int $value): static
    {
        return $this->lessThanOrEqual($value);
    }

    /**
     * LIKE operator for pattern matching
     *
     * @param  string  $pattern  Pattern to match (use * as wildcard)
     */
    public function like(string $pattern): static
    {
        return $this->addCondition('like', $pattern);
    }

    /**
     * IN operator - value must be in the given array
     *
     * @param  array  $values  Array of values
     */
    public function in(array $values): static
    {
        return $this->addCondition('in', $values);
    }

    /**
     * NOT IN operator - value must not be in the given array
     *
     * @param  array  $values  Array of values
     */
    public function notIn(array $values): static
    {
        return $this->addCondition('notIn', $values);
    }

    /**
     * IS NULL operator - property has no value
     */
    public function isNull(): static
    {
        return $this->addCondition('isNull', null);
    }

    /**
     * IS NOT NULL operator - property has a value
     */
    public function isNotNull(): static
    {
        return $this->addCondition('isNotNull', null);
    }

    /**
     * CONTAINS operator - for set operations
     * Checks if a set contains the specified value
     *
     * @param  string  $value  Value to check
     */
    public function contains(string $value): static
    {
        return $this->addCondition('contains', $value);
    }

    /**
     * CONTAINS ANY operator - for set operations
     * Checks if a set contains any of the specified values
     *
     * @param  array  $values  Values to check
     */
    public function containsAny(array $values): static
    {
        return $this->addCondition('containsAny', $values);
    }

    /**
     * CONTAINS ALL operator - for set operations
     * Checks if a set contains all of the specified values
     *
     * @param  array  $values  Values to check
     */
    public function containsAll(array $values): static
    {
        return $this->addCondition('containsAll', $values);
    }

    /**
     * CONTAINS EXACTLY operator - for set operations
     * Checks if a set contains exactly the specified values
     *
     * @param  array  $values  Values to check
     */
    public function containsExactly(array $values): static
    {
        return $this->addCondition('containsExactly', $values);
    }

    /**
     * IS EMPTY operator - for set operations
     * Checks if a set is empty
     */
    public function isEmpty(): static
    {
        return $this->addCondition('isEmpty', null);
    }

    /**
     * Add another condition to the current filter with AND logic
     *
     * @param  string  $property  Property to filter on
     */
    public function andWhere(string $property): static
    {
        $this->currentProperty = $property;

        return $this;
    }

    /**
     * Convert filter to API string format
     */
    public function toString(): string
    {
        return $this->buildFilterString($this->conditions);
    }

    /**
     * Allow casting to string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Add a condition to the filter
     *
     * @param  string  $operator  The operator to use
     * @param  mixed  $value  The value to compare (null for operators like isNull)
     */
    protected function addCondition(string $operator, mixed $value): static
    {
        if ($this->currentProperty === null) {
            throw new \LogicException('Cannot add condition without a property. Use where() or a property method first.');
        }

        $this->conditions[] = [
            'type' => 'comparison',
            'property' => $this->currentProperty,
            'operator' => $operator,
            'value' => $value,
        ];

        return $this;
    }

    /**
     * Recursively build filter string from conditions
     *
     * @param  array  $conditions  Conditions to build
     */
    protected function buildFilterString(array $conditions): string
    {
        if (count($conditions) === 0) {
            return '';
        }

        if (count($conditions) === 1) {
            $condition = $conditions[0];

            // Handle logical operators (and, or)
            if ($condition['type'] === 'logical') {
                $operator = $condition['operator'];
                $subFilters = array_map(
                    fn ($filter) => $filter->toString(),
                    $condition['filters']
                );

                return $operator.'('.implode(', ', $subFilters).')';
            }

            // Handle comparison operators
            return $this->buildComparisonString($condition);
        }

        // Multiple conditions - implicit AND
        $filters = array_map(
            fn ($condition) => $condition['type'] === 'logical'
                ? $this->buildFilterString([$condition])
                : $this->buildComparisonString($condition),
            $conditions
        );

        return 'and('.implode(', ', $filters).')';
    }

    /**
     * Build comparison string for a single condition
     *
     * @param  array  $condition  Condition to build
     */
    protected function buildComparisonString(array $condition): string
    {
        $property = $condition['property'];
        $operator = $condition['operator'];

        // Operators without values
        if (in_array($operator, ['isNull', 'isNotNull', 'isEmpty'])) {
            return "{$property}.{$operator}()";
        }

        $value = $condition['value'];

        // Handle array values (in, notIn, containsAny, containsAll, containsExactly)
        if (is_array($value)) {
            $formattedValues = array_map(
                fn ($v) => $this->formatValue($v),
                $value
            );

            return "{$property}.{$operator}(".implode(', ', $formattedValues).')';
        }

        // Handle single values
        $formattedValue = $this->formatValue($value);

        return "{$property}.{$operator}({$formattedValue})";
    }

    /**
     * Format value for filter string
     *
     * @param  mixed  $value  Value to format
     */
    protected function formatValue(mixed $value): string
    {
        // Handle enums - extract their value
        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return (string) $value;
        }

        // String - escape single quotes and wrap in single quotes
        $escaped = str_replace("'", "\\'", (string) $value);

        return "'{$escaped}'";
    }
}
