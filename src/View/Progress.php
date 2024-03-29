<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

/**
 * @package ChriCo\Fields\View
 */
class Progress implements RenderableElementInterface
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
            '<progress %s>%s</progress>',
            $this->attributesToString($attributes),
            $this->escapeHtml($element->value())
        );
    }
}
