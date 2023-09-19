<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\LabelAwareInterface;

/**
 * Class Input
 *
 * @package ChriCo\Fields\View
 */
class Input implements RenderableElementInterface
{
    use AttributeFormatterTrait;

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        $attributes = $element->attributesForView();
        $attributes = $this->buildCssClasses($attributes, 'element', $element);

        return sprintf(
            '<input %s />',
            $this->attributesToString($attributes)
        );
    }
}
