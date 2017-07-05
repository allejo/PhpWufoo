<?php

namespace allejo\Wufoo;

/**
 * An EntryFilter is used to filter through individual entries on a form.
 *
 * @api
 * @since 0.1.0
 *
 * @method static contains(string $v)
 * @method static doesNotContain(string $v)
 * @method static beginsWith(string $v)
 * @method static endsWith(string $v)
 * @method static lessThan(integer $v)
 * @method static greaterThan(integer $v)
 * @method static on(\DateTime $v)
 * @method static before(\DateTime $v)
 * @method static after(\DateTime $v)
 * @method static equals(mixed $v)
 * @method static notEqualTo(mixed $v)
 * @method static notNull()
 */
class EntryFilter
{
    private static $filterMapping = [
        'contains' => 'Contains',
        'doesNotContain' => 'Does_not_contain',
        'beginsWith' => 'Begins_with',
        'endsWith' => 'Ends_with',
        'lessThan' => 'Is_less_than',
        'greaterThan' => 'Is_greater_than',
        'on' => 'Is_on',
        'before' => 'Is_before',
        'after' => 'Is_after',
        'equals' => 'Is_equal_to',
        'notEqualTo' => 'Is_not_equal_to',
        'notNull' => 'Is_not_NULL',
    ];

    private $fieldName;
    private $filter;
    private $value;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __toString()
    {
        if ($this->value instanceof \DateTime)
        {
            $this->value = $this->value->format('Y-m-d H:i:s');
        }

        if ($this->value)
        {
            $query = sprintf('%s %s %s', $this->fieldName, $this->filter, $this->value);
        }
        else
        {
            $query = sprintf('%s %s', $this->fieldName, $this->filter);
        }

        return urlencode($query);
    }

    public function __call($name, $arguments)
    {
        $this->filter = self::$filterMapping[$name];
        $this->value = array_pop($arguments);

        return $this;
    }

    /**
     * Convenience function to create an EntryFilter that can be used for immediate chaining.
     *
     * @api
     *
     * @param string $field The API Field ID to use in this filter
     *
     * @since 0.1.0
     *
     * @return EntryFilter
     */
    public static function create($field)
    {
        $filter = new self($field);

        return $filter;
    }
}
