<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

/**
 * Trait LabelAwareTrait
 *
 * @package ChriCo\Fields
 */
trait LabelAwareTrait
{
    protected string $label = '';
    protected array $labelAttributes = [];

    /**
     * @return string
     */
    public function label(): string
    {
        return (string) $this->label;
    }

    /**
     * @param string $label
     *
     * @return self
     */
    public function withLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array
     */
    public function labelAttributes(): array
    {
        return $this->labelAttributes;
    }

    /**
     * @param array $labelAttributes
     *
     * @return self
     */
    public function withLabelAttributes(array $labelAttributes = [])
    {
        $this->labelAttributes = $labelAttributes;

        return $this;
    }
}
