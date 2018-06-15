<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * @package ChriCo\Fields\View
 */
class Radio implements RenderableElementInterface
{

    use AttributeFormatterTrait;

    /**
     * @param ElementInterface|ChoiceElementInterface $element
     *
     * @throws InvalidClassException
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        if (! $element instanceof ChoiceElementInterface) {
            throw new InvalidClassException(
                sprintf(
                    'The given element "%s" has to implement "%s"',
                    $element->name(),
                    ChoiceElementInterface::class
                )
            );
        }
        $list = $element->choices();
        $attributes = $element->attributes();
        $choices = $list->choices();
        $selected = $list->choicesForValue((array) $element->value());

        $html = [];

        foreach ($choices as $key => $name) {
            $elementAttr = $attributes;
            $elementAttr['id'] .= '_'.$key;
            $elementAttr['value'] = $key;
            $elementAttr['checked'] = isset($selected[$key]);

            $label = sprintf(
                '<label for="%s">%s</label>',
                $this->escapeAttribute($elementAttr['id']),
                $this->escapeHtml($name)
            );

            $html[] = sprintf(
                '<p><input %s /> %s</p>',
                $this->attributesToString($elementAttr),
                $label
            );
        }

        return implode(' ', $html);
    }
}
