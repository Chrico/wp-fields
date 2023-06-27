<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * Class Checkbox
 *
 * @package ChriCo\Fields\View
 */
class Checkbox implements RenderableElementInterface
{
    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    /**
     * @param ElementInterface|ChoiceElementInterface $element
     *
     * @return string
     * @throws InvalidClassException
     *
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, ChoiceElementInterface::class);

        $list = $element->choices();
        $attributes = $element->attributes();
        $choices = $list->choices();
        $selected = $list->choicesForValue((array) $element->value());

        $html = [];

        if (count($choices) < 1) {
            return '';
        }

        $isMultiChoice = false;
        if (count($choices) > 1) {
            $attributes['name'] = $element->name() . '[]';
            $isMultiChoice = true;
        }

        foreach ($choices as $key => $choice) {
            $elementAttr = $attributes;
            $elementAttr['value'] = $key;
            $elementAttr['checked'] = isset($selected[$key]);
            $elementAttr['disabled'] = $choice['disabled'];
            $elementAttr['id'] = $isMultiChoice
                ? $element->id() . '_' . $key
                : $element->id();
            $elementAttr = $this->buildCssClasses($elementAttr, 'element', $element);

            $label = sprintf(
                '<label for="%s">%s</label>',
                $this->escapeAttribute($elementAttr['id']),
                $this->escapeHtml($choice['label'])
            );

            $html[] = sprintf(
                '<p><input %s /> %s</p>',
                $this->attributesToString($elementAttr),
                $label
            );
        }

        return implode('', $html);
    }
}
