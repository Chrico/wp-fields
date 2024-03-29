<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

interface LabelAwareInterface
{

    /**
     * @return string $label
     */
    public function label(): string;

    /**
     * @param string $label
     */
    public function withLabel(string $label);

    /**
     * @return  array $labelAttributes
     */
    public function labelAttributes(): array;

    /**
     * @param array $labelAttributes
     */
    public function withLabelAttributes(array $labelAttributes = []);
}
