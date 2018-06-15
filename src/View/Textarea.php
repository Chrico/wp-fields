<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

/**
 * @package ChriCo\Fields\View
 */
class Textarea implements RenderableElementInterface
{

    use AttributeFormatterTrait;

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        $attributes = $element->attributes();
        unset($attributes['value']);

        return sprintf(
            '<textarea %s>%s</textarea>',
            $this->attributesToString($attributes),
            $this->escapeHtml($element->value())
        );
    }
}
