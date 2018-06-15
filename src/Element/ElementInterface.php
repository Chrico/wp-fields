<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

/**
 * Interface ElementInterface
 *
 * @package ChriCo\Fields\Element
 */
interface ElementInterface
{

    /**
     * Proxy to access the "disabled" field attribute with cast to boolean.
     *
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * Proxy to get the "id" in field attributes.
     *
     * @return string
     */
    public function id(): string;

    /**
     * Proxy to get the "type" in field attributes.
     *
     * @return string
     */
    public function type(): string;

    /**
     * Proxy to get the name in field attributes.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Proxy to get the value in field attributes.
     *
     * @return mixed
     */
    public function value();

    /**
     * Proxy to set the value in field attributes.
     *
     * @param mixed $value
     */
    public function withValue($value);

    /**
     * Get all field attributes for this element.
     *
     * @return array
     */
    public function attributes(): array;

    /**
     * @param array $attributes
     */
    public function withAttributes(array $attributes = []);

    /**
     * @param string $key
     * @param int|string|bool $value
     */
    public function withAttribute(string $key, $value);

    /**
     * @param string $key
     *
     * @return int|string|bool $value
     */
    public function attribute(string $key);

    /**
     * Get all field options for this element.
     *
     * @return array
     */
    public function options(): array;

    /**
     * Set specific options which can be used in e.G. JavaScript.
     *
     * @param array $options
     */
    public function withOptions(array $options = []);

    /**
     * @param string $key
     * @param int|string $value
     */
    public function withOption(string $key, $value);

    /**
     * @param string $key
     *
     * @return int|string $value
     */
    public function option(string $key);
}
