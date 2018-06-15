<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\DescriptionAwareTrait;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

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

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $options = [];

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
     * @return Element
     */
    public function withAttribute(string $key, $value): ElementInterface
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
        if (! isset($this->attributes[$key])) {
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
        return $this->attribute('value');
    }

    /**
     * @param string $value
     *
     * @return Element
     */
    public function withValue($value): Element
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
     * @return Element
     */
    public function withAttributes(array $attributes = []): Element
    {
        $this->attributes = array_merge(
            $this->attributes,
            $attributes
        );

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
     * @return Element
     */
    public function withOptions(array $options = []): Element
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
     * @return Element
     */
    public function withOption(string $key, $value): Element
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
        if (! isset($this->options[$key])) {
            return '';
        }

        return $this->options[$key];
    }
}
