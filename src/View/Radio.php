<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\LabelAwareInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * @package ChriCo\Fields\View
 */
class Radio implements RenderableElementInterface
{

    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    /**
     * @param ElementInterface|ChoiceElementInterface $element
     *
     * @throws InvalidClassException
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, ChoiceElementInterface::class);

        $list = $element->choices();
        $attributes = $element->attributes();
        $choices = $list->choices();
        $selected = $list->choicesForValue((array) $element->value());

        $html = [];

        foreach ($choices as $key => $choice) {
            $elementAttr = $attributes;
            $elementAttr['id'] .= '_'.$key;
            $elementAttr['value'] = $key;
            $elementAttr['disabled'] = $choice['disabled'];
            $elementAttr['checked'] = isset($selected[$key]);

            $label = sprintf(
                '<label for="%s">%s</label>',
                $this->escapeAttribute($elementAttr['id']),
                $choice['label']
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
