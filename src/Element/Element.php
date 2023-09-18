<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

/**
 * Class Element
 *
 * @package ChriCo\Fields\Element
 */
class Element implements
    ElementInterface,
    LabelAwareInterface,
    ErrorAwareInterface,
    DescriptionAwareInterface
{
    use ErrorAwareTrait;
    use DescriptionAwareTrait;
    use LabelAwareTrait;

    protected array $attributes = [];

    protected array $options = [];

    /**
     * @var callable|null
     */
    protected $validator = null;

    /**
     * @var callable|null
     */
    protected $filter = null;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->withAttribute('name', $name);
        $this->withAttribute('id', $name);
    }

    /**
     * @param string $key
     * @param bool|int|string $value
     *
     * @return static
     */
    public function withAttribute(string $key, $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return (string) $this->attribute('id');
    }

    /**
     * @param string $key
     *
     * @return bool|int|mixed|string
     */
    public function attribute(string $key)
    {
        if (!isset($this->attributes[$key])) {
            return '';
        }

        return $this->attributes[$key];
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        $disabled = $this->attribute('disabled');

        return is_bool($disabled) && $disabled;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return (string) $this->attribute('name');
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return (string) $this->attribute('type');
    }

    /**
     * @return bool|int|mixed|string
     */
    public function value()
    {
        $value = $this->attribute('value');

        return $this->filter($value);
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function withValue($value): static
    {
        $this->withAttribute('value', $value);

        return $this;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    public function withAttributes(array $attributes = []): static
    {
        foreach ($attributes as $key => $value) {
            $this->withAttribute($key, $value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return static
     */
    public function withOptions(array $options = []): static
    {
        $this->options = array_merge(
            $this->options,
            $options
        );

        return $this;
    }

    /**
     * @param string $key
     * @param int|string $value
     *
     * @return static
     */
    public function withOption(string $key, $value): static
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return int|mixed|string
     */
    public function option(string $key)
    {
        if (!isset($this->options[$key])) {
            return '';
        }

        return $this->options[$key];
    }

    /**
     * @param callable $callable
     *
     * @return static
     */
    public function withFilter(callable $callable): static
    {
        $this->filter = $callable;

        return $this;
    }

    public function filter($value)
    {
        if ($this->filter) {
            $value = ($this->filter)($value, $this);
        }

        return $value;
    }

    /**
     * @param callable $callable
     *
     * @return static
     */
    public function withValidator(callable $callable): static
    {
        $this->validator = $callable;

        return $this;
    }

    public function validate(): bool
    {
        $value = $this->value();

        $valid = true;
        if ($this->validator) {
            $error = ($this->validator)($value, $this);
            if (is_wp_error($error)) {
                $this->withErrors($error->get_error_messages());
                $valid = false;
            }
        }

        return $valid;
    }
}
