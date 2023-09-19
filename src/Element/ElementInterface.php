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
     * In case the element itself (or parent) is submitted.
     *
     * @return bool
     */
    public function isSubmitted(): bool;

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
     * @return bool|int|mixed|string|array
     */
    public function value();

    /**
     * Proxy to set the value in field attributes.
     *
     * @param mixed $value
     */
    public function withValue($value);

    /**
     * Returns attributes prepared for the View with taking
     * the parent into consideration for building correct
     * "id" and "name" attributes.
     *
     * @return array
     */
    public function attributesForView(): array;

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
     * @return bool|int|mixed|string|array $value
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

    /**
     * @param $value
     *
     * @return mixed
     */
    public function filter($value);

    /**
     * @param callable $callable
     */
    public function withFilter(callable $callable);

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @param callable $callable
     */
    public function withValidator(callable $callable);

    /**
     * Setting a parent which can be either a Collection or Form itself
     * to reuse internally to detect if the Element is disabled or submitted.
     *
     * @param CollectionElement $element
     */
    public function withParent(CollectionElement $element);

    /**
     * @return CollectionElement|null
     */
    public function parent(): ?CollectionElement;
}
