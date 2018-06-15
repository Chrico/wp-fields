<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

/**
 * Trait AttributeFormatterTrait
 *
 * @package ChriCo\Fields\View
 */
trait AttributeFormatterTrait
{

    /**
     * @var array
     */
    protected $booleanAttributes = [
        'autofocus',
        'checked',
        'disabled',
        'multiple',
        'readonly',
        'required',
        'selected',
    ];

    /**
     * Formatting a given array into a key="value"-string for each entry.
     *
     * @param array $attributes
     *
     * @return string $html
     */
    public function attributesToString(array $attributes = []): string
    {
        $html = [];
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }

            if (in_array($key, $this->booleanAttributes, true)) {
                $value = $this->prepareBooleanAttribute($key, $value);
            }

            if ($value === '') {
                continue;
            }

            $html[] = sprintf(
                '%s="%s"',
                $this->escapeAttribute($key),
                $this->escapeAttribute($value)
            );
        }

        return implode(' ', $html);
    }

    /**
     * @param string $key
     * @param string|bool $value
     *
     * @return string
     */
    protected function prepareBooleanAttribute(string $key, $value): string
    {
        if ($value === $key || (is_bool($value) && $value)) {
            return $key;
        }

        return '';
    }

    /**
     * Wrapper for WordPress function esc_attr().
     *
     * @param string|int $value
     *
     * @return string $value
     */
    public function escapeAttribute($value): string
    {
        return esc_attr((string) $value);
    }

    /**
     * Wrapper for WordPress function esc_html().
     *
     * @param string|int $value
     *
     * @return string $value
     */
    public function escapeHtml($value): string
    {
        return esc_html((string) $value);
    }
}
